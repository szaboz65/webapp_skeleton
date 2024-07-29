class Prefs {
    constructor (name) {
        this.name = name
        this.data = {}
    }

    init (defaults) {
        // init preferences
        try {
            this.data = localStorage.getItem(app.name + '-prefs')
            this.data = JSON.parse(this.data)
        } catch (e) {
            this.data = null
        }
        if (this.data === null) {
            this.data = defaults || {}
            localStorage.setItem(this.name + '-prefs', JSON.stringify(this.data))
        }
    }

    set (name, value) {
        if (name === null) return
        this.data[name] = value
        localStorage.setItem(this.name + '-prefs', JSON.stringify(this.data))
    }

    get (name) {
        if (name === null) return null
        return this.data[name]
    }
}
export default Prefs
