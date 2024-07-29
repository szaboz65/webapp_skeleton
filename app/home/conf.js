// ============================================
// -- HOME page Configuration

export default {
    home_menuitem: {
        id: 'home',
        text: 'Home',
        type: 'radio',
        group: 'main',
        icon: 'icon-home',
        route: '/home'
    },

    // --- Home sidebar
    home_sb: {
        name: 'home_sb',
        nodes: [
            {id: 'home-users', text: 'Users', img: 'icon-folder', expanded: true, group: true,
                nodes: [
                    {
                        id: 'users',
                        text: 'Users',
                        icon: 'icon-user',
                        route: '/home/users'
                    },
                    {
                        id: 'usertypes',
                        text: 'Usertypes',
                        icon: 'icon-users',
                        route: '/home/usertypes',
                        level: 1
                    }
                ]
            }
        ]
    }
};