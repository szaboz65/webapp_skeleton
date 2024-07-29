import { w2ui, w2alert, w2utils, query } from './libs/w2ui/w2ui.es6.js'
import Router from './main/util/router.js'
import Main from './main/main.js'

class App {
    constructor () {
        this.name = 'WebApp'
        this.context = 'api/'
        this.router = new Router()
        this.modules = []

        this.router.on('error', (event) => {
            w2alert(`Route "${event.detail.hash}" is not defined.`)
            this.router.go('/')
        })
    }

    getName () { return this.name }
    getContextUrl (url, recid) { return this.context + url + (recid ? ('/' + recid) : '') }

    hasModule (key) { return key in app.modules }
    addModule (key, mod) { app.modules[key] = mod }

    lock (message) {
        if (!message) { message = 'Loading...' }
        w2utils.lock('body', message, true)
    }

    unlock () {
        w2utils.unlock('body')
    }

    error (msg, silent, forced) {
        console.log('ERROR: ' + msg)
        if (silent === true) { return }
        if (forced === true || query('#w2ui-popup').length === 0) {
            w2alert(msg, 'Error')
        }
    }

    isSaveError (data, silent, forced) {
        if (typeof data === 'undefined') {
            this.error('Error!')
            return true
        }

        if (typeof data.status !== 'undefined' && data.status === 'success') { return false }
        if (typeof data.error === 'undefined' || data.error === false) { return false }

        if (typeof data.error.message !== 'undefined') {
            this.error('Error: ' + data.error.message, silent, forced)
        } else if (typeof data.message !== 'undefined') {
            this.error('Error: ' + data.message, silent, forced)
        } else {
            this.error('Error without message!', silent, forced)
        }
        return true
    }

    run () {
        this.main = new Main(this)
        this.main.start(this)
    }
}

w2utils.extend(w2utils, {prepareParams: function(url, fetchOptions, defDataType) {
    let dataType = defDataType ?? w2utils.settings.dataType
    let postParams = fetchOptions.body
    if (fetchOptions.method == 'GET') {
        if (typeof postParams.recid !== 'undefined') {
            // form
            delete fetchOptions.body
        } else {
            // grid or tooltip
            postParams = { request: postParams } //??????
            body2params()
        }
    } else if (fetchOptions.method == 'POST') {
        // form
        if (postParams?.recid > 0) {
            fetchOptions.method = 'PUT'
        }
        fetchOptions.body = fetchOptions.body.record
        fetchOptions.headers['Content-Type'] = 'application/json'
    } else if (fetchOptions.method == 'PUT') {
        // grid
        fetchOptions.body = fetchOptions.body.changes
        fetchOptions.headers['Content-Type'] = 'application/json'
    } else {
        // delete
        fetchOptions.headers['Content-Type'] = 'application/json'
        //fetchOptions.method = 'POST'
    }
    fetchOptions.body = typeof fetchOptions.body == 'string' ? fetchOptions.body : JSON.stringify(fetchOptions.body)
    return fetchOptions
    function body2params() {
        Object.keys(postParams).forEach(key => {
            let param = postParams[key]
            if (typeof param == 'object') param = JSON.stringify(param)
            url.searchParams.append(key, param)
        })
        delete fetchOptions.body
    }
}})

const app = new App()

window.w2ui = w2ui
window.app = app

app.run()

export default app
