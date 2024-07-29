import { w2utils } from './../../libs/w2ui/w2ui.es6.js'

function setLang (grid, config) {
    for (let i = 0; i < grid.columns.length; i++) {
        grid.columns[i].text = w2utils.lang(config.columns[i].text)
    }
    for (let i = 0; i < grid.searches.length; i++) {
        grid.searches[i].text = w2utils.lang(config.searches[i].text)
    }
}

function reload (grid, data, callBack) {
    let sel = 0
    if (typeof data.insertedid !== 'undefined') {
        sel = data.insertedid
    } else {
        const selection = w2ui[grid].getSelection()
        if (selection.length === 1) {
            sel = selection[0]
        }
    }
    w2ui[grid].reload(function () {
        if (sel) {
            w2ui[grid].select(sel)
        }
        if (typeof callBack === 'function') {
            callBack()
        }
    })
}

function get_yes_no_items () {
    return [{ id: '0', text: 'No' }, { id: '1', text: 'Yes' }]
}

function get_yes_no_text (bool) {
    if (bool === null) {
        return ''
    }
    if (typeof bool !== 'boolean') {
        bool = parseInt(bool)
    }
    bool = bool === false ? 0 : 1
    return ['No', 'Yes'][bool]
}

export default { setLang, reload, get_yes_no_items, get_yes_no_text }
