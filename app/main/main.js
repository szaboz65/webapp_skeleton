import { query, w2utils, w2toolbar, w2layout, w2popup, w2form, w2grid } from './../libs/w2ui/w2ui.es6.js'
import Session from './util/session.js'
import Prefs from './util/prefs.js'
import ReleaseNotes from './release_notes.js'
import version from './version.js'
import conf from './conf.js'
import login from './login.js'
import Modules from './modules.js'

class Main {
    constructor (app) {
        this.loc = '/'
        this.prefs = new Prefs(app.getName())
        this.prefs.init({
            'ui-sidebar-size': 'large'
        })
        this.session = new Session(app.getContextUrl(''))

        // layout
        this.app_layout = new w2layout(conf.app_layout)
        this.app_toolbar = new w2toolbar(conf.app_toolbar)

        // toolbarhandler
        const obj = this
        this.app_toolbar.onClick = (event) => {
            switch (event.target) {
                case 'user:logout':
                    obj.userLogout()
                    break
                case 'help:version':
                    obj.showVersion()
                    break
                case 'help:release_notes':
                    obj.showReleaseNotes()
                    break
                default: {
                    // delegate event to the menu item onSelect()
                    const parts = event.target.split(':')
                    if (parts.length > 1) {
                        let ev = null
                        if (typeof event.detail !== 'undefined') {
                            ev = event.detail
                        } else if (typeof event.item !== 'undefined') {
                            ev = event
                        }
                        if (ev && (typeof ev.item !== 'undefined') && (ev.item.type === 'menu')) {
                            if ((typeof ev.subItem !== 'undefined') && (typeof ev.subItem.onSelect === 'function')) {
                                console.log('TRACE: ' + event.target + '.onSelect.')
                                ev.subItem.onSelect(event)
                            }
                        }
                    }
                }
            }
        }

        // define w2ui global settings
        w2utils.settings.weekStarts = 'M'
        w2utils.settings.dateFormat = 'yyyy-mm-dd'
        w2utils.settings.timeFormat = 'hh24:mm'
        w2utils.settings.datetimeFormat = 'yyyy-mm-dd|hh24:mm'
        // w2utils.settings.groupSymbol = ',';
        w2utils.settings.dataType = 'JSON'
        // w2utils.settings.currencyPrefix = '&euro;';
        // w2utils.settings.currencySuffix = '';
        // w2utils.settings.currencyPrecision = '0';
    }

    start (app) {
        const getSession = (force, callback) => {
            // if already logged in
            const userInfo = this.session.getUserInfo()
            if (userInfo?.getUserID() !== 0 && force !== true) {
                if (typeof callback === 'function') {
                    callback(userInfo)
                }
            } else {
                this.session.getSession(force, callback)
            }
        }
        const checkSession = (userInfo) => {
            if (userInfo?.getUserID() === 0 || !userInfo?.isSessionActive()) {
                return false
            }
            return true
        }
        const startTimer = () => {
            setTimeout(() => {
                getSession(true, (userInfo) => {
                    if (checkSession(userInfo)) {
                        startTimer()
                    } else {
                        this.userLogin()
                    }
                })
            }, 300000)
        }
        const render = () => {
            this.app_toolbar.set('swversion', { html: 'Version: ' + version.version + '<br>' + version.builddate })
            this.setUserName(this.session.getUserInfo())

            this.app_toolbar.render('#app-toolbar')
            this.app_layout.render('#app-main')
            query('#app-container').show()
        }

        // check session
        getSession(true, (userInfo) => {
            if (typeof userInfo === 'string') {
                app.error(userInfo)
                return
            }
            if (!checkSession(userInfo)) {
                this.userLogin()
                return
            }

            // init modules
            Modules(app)

            // start app
            render()
            app.router.init(this.loc)
            startTimer()
        })
    }

    userLogin () {
        this.loc = app.router.get()
        console.log(this.loc)
        this.getClearLayout()
        this.app_layout.render('#app-main')
        query('#app-toolbar').html('')
        query('#app-container').show()
        if (query('#w2ui-popup').length > 0) {
            w2popup.close()
        }
        login(app, () => {
            this.start(app)
        })
    }

    userLogout () {
        this.loc = app.router.set('/')
        this.loc = app.router.get()
        console.log(this.loc)
        this.session.logout(() => {
            this.userLogin()
        })
    }

    getToolbar (name) {
        if (typeof name === 'undefined') {
            return this.app_toolbar
        }
        return this.app_toolbar.get(name)
    }

    selectToolbar (name) {
        this.app_toolbar.uncheck(...this.app_toolbar.get())
        this.app_toolbar.check(name)
    }

    addMenuItem (name, item) {
        const menu = this.getToolbar(name)
        if (menu && menu.type === 'menu') {
            menu.items.push(item)
        }
    }

    getLayout () {
        return this.app_layout
    }

    getClearLayout () {
        this.app_layout.hide('left', true)
        this.app_layout.hide('right', true)
        this.app_layout.hide('preview', true)
        return this.app_layout
    }

    setUserName (userInfo) {
        this.app_toolbar.set('user', { text: userInfo.getUserID() ? userInfo.getUserName() : 'No login' })
    }

    isFlatSmall () {
        return this.prefs.get('ui-sidebar-size') === 'small'
    }

    goFlat (toFlat) {
        if (toFlat === true) {
            this.app_layout.set('left', { size: 35, minSize: 35, resizable: false })
            this.prefs.set('ui-sidebar-size', 'small')
        } else {
            this.app_layout.set('left', { size: 180, minSize: 100, resizable: true })
            this.prefs.set('ui-sidebar-size', 'large')
        }
    }

    getPref (name) {
        return this.prefs.get(name)
    }

    setPref (name, value) {
        this.prefs.set(name, value)
    }

    openInNewTab (url) {
        const win = window.open(url, '_blank')
        win.focus()
    }

    showVersion () {
        const VersionForm = new w2form(w2utils.extend({
            name: 'VersionForm',
            url: app.getContextUrl('version'),
            recid: 'api',
            onLoad: event => {
                event.onComplete = function () {
                    w2utils.extend(this.record, version)
                }
            },
            onRender: event => {
                event.onComplete = function () {
                    this.disable('version', 'builddate', 'apiversion', 'apibuilddate')
                }
            }
        }, conf.VersionForm))

        w2popup.open(w2utils.extend({
            body: '<div id="form" style="width: 100%; height: 100%;"></div>'
        }, conf.version_popup))
                .then(e => {
                    VersionForm.render(query('#w2ui-popup #form')[0])
                })
                .close(e => {
                    VersionForm.destroy()
                })
    }

    showReleaseNotes () {
        const ReleaseNotesGrid = new w2grid(w2utils.extend({
            name: 'ReleaseNotesGrid',
            show: {
                toolbar: false
            },
            records: ReleaseNotes || []
        }, conf.release_notes_grid))

        w2popup.open(w2utils.extend({
            body: '<div id="form" style="width: 100%; height: 100%;"></div>'
        }, conf.release_notes_popup))
                .then(e => {
                    ReleaseNotesGrid.render(query('#w2ui-popup #form')[0])
                })
                .close(e => {
                    ReleaseNotesGrid.destroy()
                })
    }
}

export default Main
