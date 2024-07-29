class Roles {
    static ROLE_QUEST = 0
    static ROLE_ADMIN = 1
    static ROLE_SALES = 2

    static isAdmin () {
        return window.app.main.session.getUserInfo().hasRole(Roles.ROLE_ADMIN)
    }

    static isSales () {
        return window.app.main.session.getUserInfo().hasRole(Roles.ROLE_SALES)
    }
}

export default Roles
