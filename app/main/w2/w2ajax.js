import { query, w2utils } from './../../libs/w2ui/w2ui.es6.js'

const doAjax = async (request, callBack) => {
    return new Promise((resolve, reject) => {
        fetch(request)
            .catch(processError)
            .then((resp) => {
                if (resp?.status !== 200) {
                    // if resp is undefined, it means request was aborted
                    if (resp) processError(resp)
                    return
                }
                resp.json()
                    .catch(processError)
                    .then(data => {
console.log(data)
                        // call back
                        if (typeof callBack === 'function') {
                            callBack(data)
                        }
                        resolve(data)
                    })
            })

        function processError (response) {
            if (response.name === 'AbortError') {
                // request was aborted by the form
                return
            }
            // default behavior
            if (response.status && response.status !== 200) {
                console.log(response.status + ': ' + response.statusText)
            } else {
                console.log('ERROR: Server request failed.', response, '. ')
            }
            reject(response)
        }
    })
}

const getItems = async (url, postData, callBack) => {
    if (typeof postData === 'undefined') postData = {}
    const request = new Request(url, {
        method: 'GET',
        cache: 'no-cache'
    })
    return doAjax(request)
        .then(tmp => {
            if (typeof tmp.error === 'undefined') {
                if (typeof callBack === 'function') {
                    callBack(tmp)
                }
                return tmp
            }
            return []
        })
}

const loadImg = async (url, box, callBack) => {
    const request = new Request(url, {
        method: 'GET',
        cache: 'no-cache'
    })
    return fetch(request)
        .then(response => response.text())
        .then(text => {
            if (text.indexOf('data:') !== -1) {
                const draw = (typeof box === 'string') ? query(box).get(0) : box
                if (box) {
                    const image = new Image()
                    image.width = draw.width
                    image.height = draw.height
                    image.class = draw.class
                    image.style = draw.style
                    image.id = draw.id
                    image.src = text
                    draw.replaceWith(image)
                    if (typeof callBack === 'function') {
                        callBack()
                    }
                }
            }
        })
}

export default { doAjax, getItems, loadImg }
