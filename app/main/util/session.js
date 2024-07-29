import UserInfo from './userinfo.js'
import W2Ajax from '../w2/w2ajax.js'

class Session {
    constructor (context) {
        this.context = context
        this.userInfo = new UserInfo()
    }

    getUserInfo () {
        return this.userInfo
    }

    logout (callback) {
        this.userInfo.clear()
        W2Ajax.doAjax(this.context + 'logout')
            .then(data => {
                if (typeof callback === 'function') callback(data?.error === false)
            })
            .catch(console.log)
    }

    getSession (force, callback) {
        this.userInfo.clear()
        W2Ajax.doAjax(this.context + 'session')
            .then(data => {
                if (data?.user) {
                    data.user.pref = data.pref
                    data.user.type = data.type
                    data.user.roles = data.roles
                    data.user.session = data.session
                    this.userInfo.set(data.user)
                }
                if (typeof callback === 'function') callback(this.userInfo)
            })
            .catch(error => {
                if (typeof callback === 'function' && force) callback(error.message)
            })
    }
}

export default Session
