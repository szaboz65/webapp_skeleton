import { w2sidebar } from './../../libs/w2ui/w2ui.es6.js'

class FlatSidebar extends w2sidebar {
    constructor (options, app) {
        super(Object.assign(options, {
            flatButton: true,
            onFlat (event) {
                if (app) app.main.goFlat(event.detail.goFlat)
            },
            onRender (event) {
                event.done(function () {
                    if (app) {
                        const isflatsmall = app.main.isFlatSmall()
                        if (isflatsmall && this.flat !== true) {
                            this.goFlat(true)
                        }
                        if (!isflatsmall && this.flat === true) {
                            this.goFlat(false)
                        }
                    }
                })
            },
            onClick (event) {
                if (event.object.route) {
                    app.router.go(event.object.route)
                } else {
                    action.call(this, event)
                }
            }
        }))
    }

    getLevel (id) {
        const current = this.get(id)
        if (!current) { return false }
        return current.level || 0
    }

    getSelectedRoute (route) {
        let result = this.find({ selected: true, disabled: false, hidden: false })
        result = (result.length && result[0].route) || route
        return result
    }

    showRoles () {
        showRoles(this.nodes)

        function showRoles (nodes) {
            for (let i = 0; i < nodes.length; i++) {
                const id = nodes[i].id
                if (this.hasRole(id)) {
                    this.show(id)
                    if (nodes[i].nodes.length > 0) {
                        this.showRoles(nodes[i].nodes)
                    }
                } else {
                    this.hide(id)
                    this.unselect(id)
                }
            }
        }
    }

    hasRole (id) {
        const level = this.getLevel(id)
        if (level === false) { return true }
        return window.app.main.session.hasRole(level)
    }

    onAction (event) {
    }
}
export default FlatSidebar
