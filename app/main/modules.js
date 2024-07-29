import Home from '../home/home.js'

export default function (app) {
    // init modules
    if (!app.hasModule('home')) {
        app.addModule('home', new Home(app))
    }
}
