import { w2ui, w2toolbar, w2layout, w2sidebar } from './../libs/w2ui/w2ui.es6.js'
import FlatSidebar from '../main/w2/flatsidebar.js'
import UserTypes from './usertypes/usertypes.js'
import Users from './users/users.js'
import conf from './conf.js'

class Home {
    
    constructor(app) {
        app.main.getToolbar().insert('spacer1', conf.home_menuitem);
        let home_sb = new FlatSidebar(conf.home_sb, app);
        let usertypes = null;
        let users = null;
        
        app.router.add({

            '/home*'(event) {
                app.main.selectToolbar('home');
                home_sb.unselect(['usertypes', 'users']);
                let layout = app.main.getClearLayout();
                layout.show('left', true);
                layout.html('left', home_sb);

            },

            '/'(event) {
                app.router.go(home_sb.getSelectedRoute('/home'));
            },

            '/home'(event) {
                app.router.go(home_sb.getSelectedRoute('/home/users'));
            },

            '/home/usertypes'(event) {
                home_sb.select('usertypes');
                if (!usertypes) usertypes = new  UserTypes();
                usertypes.start();
            },
            
            "/home/users"(route, params) {
                home_sb.select('users');
                if (!users) users = new  Users();
                users.start();
            }
        });
    }
    
    
};

export default Home