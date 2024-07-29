const MIN_REMAINED_TIME = 300

class UserInfo {
    constructor (userInfo) {
        this.clear()
        if (userInfo) this.set(userInfo)
    }

    clear () {
        this.userInfo = null
    }

    set (userInfo) {
        this.userInfo = userInfo
    }

    get () {
        return this.userInfo
    }

    isLoggedIn (userid) {
        return this.getUserID() === userid
    }

    getUserID () {
        return this.userInfo ? parseInt(this.userInfo.userid) : 0
    }

    getUserName () {
        return this.userInfo ? this.userInfo.name : ''
    }

    hasRole (role) {
        role = parseInt(role)
        if (role === 0) { return true }
        if (this.getUserID() === 0) { return false }
        if (!this.userInfo.roles) { return false }
        for (let n = 0; n < this.userInfo.roles.length; ++n) {
            if (parseInt(this.userInfo.roles[n].roleid) === role) { return true }
        }
        return false
    }

    isSessionActive () {
        if (this.getUserID() === 0) {
            return false
        }
        const status = this.userInfo?.session?.status
        return status === 'active' && this.userInfo.session.remained_time > MIN_REMAINED_TIME
    }
}

export default UserInfo
