import { query, w2grid, w2utils, w2form, w2popup } from './../../libs/w2ui/w2ui.es6.js';
import W2Ajax from '../../main/w2/w2ajax.js';
import GridExt from '../../main/w2/gridext.js';
import Roles from '../../main/util/roles.js';
import conf from './conf.js';

class Users {

    constructor() {

// private 
        var preview_rendering = false;
        var display_details = false;


        // init grids
        new w2grid(w2utils.extend({
            name: 'user_grid',
            url: app.getContextUrl('users'),
            recid: 'userid',
            show: {
                toolbar: true,
                toolbarAdd: Roles.isAdmin(),
                toolbarEdit: Roles.isAdmin()
            },
            toolbar: {
                items: [
                    {
                        type: 'spacer'
                    },
                    {
                        type: 'button',
                        id: 'details',
                        text: 'Show Details',
                        tooltip: 'Preview details',
                        img: 'icon-eye'
                    }
                ]
            },
            onToolbar: function (event) {
                if (event.target === 'details') {
                    !display_details ? openDetails() : closeDetails();
                }
            },
            onAdd: function (event) {
                addUser();
            },
            onEdit: function (event) {
                addUser(event.detail.recid);
            },
            onDblClick: function (event) {
                if (Roles.isAdmin())
                    addUser(event.detail.recid);
            },
            onSelect: function (event) {
                event.onComplete = function () {
                    previewUser();
                };
            },
            onUnselect: function (event) {
                event.onComplete = function () {
                    previewUser();
                };
            },
            onRefresh: function (event) {
                event.onComplete = function () {
                    previewUser();
                };
            }
        }, conf.user_grid));
        setTimeout(async () => {
            let fk_utypeid = w2ui.user_grid.getSearch('fk_utypeid');
            fk_utypeid.options.items = await W2Ajax.getItems(app.getContextUrl('usertypeitems'));

            let inactive = w2ui.user_grid.getSearch('inactive');
            inactive.options.items = GridExt.get_yes_no_items();
            
            let roles = w2ui.user_grid.getSearch('roles');
            roles.options.items = await W2Ajax.getItems(app.getContextUrl('roleitems'));
        },1);

        function openDetails() {
            display_details = true;
            var layout = app.main.getLayout();
            layout.show('right', true);
            w2ui.user_grid.toolbar.hide('details');
            previewUser();
        }
        function closeDetails() {
            display_details = false;
            var layout = app.main.getLayout();
            layout.hide('right', true);
            w2ui.user_grid.toolbar.show('details');
        }


        function previewUser() {
            if (!display_details || preview_rendering)
                return;

            let layout = app.main.getLayout();
            const grid = w2ui.user_grid;
            const sel = grid.getSelection();
            if (sel.length !== 1) {
                const msg = sel.length < 1 ? conf.msg_noselected : conf.msg_manyselected;
                layout.html('right', conf.close_box_html+conf.preview_msg_html.replace('$message', msg));
            } else {
                preview_rendering = true;
                layout.html('right', '<style>' + conf.user_view_css + '</style>' + conf.close_box_html + conf.user_view_html);
                const user = grid.get(sel[0]);
                var $dsp = query(layout.el('right'));
                $dsp.find('#name').html(user.name);
                $dsp.find('#email').html('<a href="mailto:' + user.email + '">' + user.email + '</a>');
                addDetail('ID', user.userid);
                if (user.phone)
                    addDetail('Phone', user.phone);
                if (parseInt(user.inactive) === 1)
                    addDetail('Inactive', 'Yes');
                if (user.utypename)
                    addDetail('Usertype', user.utypename);
                if (user.ses_lastlogin)
                    addActivity('Last login', user.ses_lastlogin);
                if (user.ses_lastactive)
                    addActivity('Last active', user.ses_lastactive);
                W2Ajax.loadImg(app.getContextUrl('user/' + user.userid + '/photo'), '#user-photo');
                getRoles(user.userid);
                preview_rendering = false;
            }
            query('#user_close').on('click', closeDetails);

            function formDetail(caption, body) {
                if (body === null || body === '')
                    body = '&nbsp;';
                return '<div class="w2ui-field w2ui-span7">' +
                        '     <label>' + (caption) + '</label>' +
                        '    <div class="user-details">' + body + '</div>' +
                        '</div>';
            }
            function addDetail(caption, body) {
                $dsp.find('#details').append(formDetail(caption, body));
            }
            function addActivity(caption, body) {
                $dsp.find('#activity').append(formDetail(caption, body));
            }
            function getRoles(userid) {
                W2Ajax.getItems(app.getContextUrl('user/' + userid + '/roleitems'), {}, function(roleitems) {
                    let roles = '';
                    for (const g in roleitems)
                        roles += roleitems[g].text + '<br>';
                    addDetail('Roles', roles);
                })
            }
        }


        function addUser(recid) {
            let user_form = new w2form(w2utils.extend({
                name: 'user_form',
                url: app.getContextUrl('user', recid),
                recid: recid,
                actions: {
                    'Save': function () {
                        this.action_save(this);
                    },
                    'Cancel': function () {
                        w2popup.close();
                    }
                },
                action_save: async (form) => {
                    form.save({}, function (data) {
                        if (data?.error === true) {
                            data?.details.forEach(e => {
                                form.last.errors.push({field: form.get(e.field), error: e.message});
                            });
                            form.showErrors();
                        } else {
                            GridExt.reload('user_grid', data, function () {
                                w2popup.close();
                            });
                        }
                    });
                },
                onSubmit: (event) => {
                     if(w2utils.isPlainObject( event.detail.postData.record.fk_utypeid))
                         event.detail.postData.record.fk_utypeid = event.detail.postData.record.fk_utypeid.id;
                     if(w2utils.isPlainObject( event.detail.postData.record.inactive))
                         event.detail.postData.record.inactive = event.detail.postData.record.inactive.id;
                 }
            }, conf.user_form));
            setTimeout( async () => {
                const usertype_items = await W2Ajax.getItems(app.getContextUrl('usertypeitems'));
                let fk_utypeid = user_form.get('fk_utypeid');
                fk_utypeid.options.items = usertype_items;
                let inactive = user_form.get('inactive');
                inactive.options.items = GridExt.get_yes_no_items();
            });

            w2popup.open(w2utils.extend({
                title: conf.user_popup.title + ': ' + (!recid ? 'Add New' : 'Edit'),
                body: '<div id="form" style="width: 100%; height: 100%;"></div>'
            }, conf.user_popup))
            .then(e => {
                user_form.render(query('#w2ui-popup #form')[0]);
            })
            .close(e => {
                user_form.destroy();
            });
        }

    }

    start() {
        var layout = app.main.getLayout();
        layout.html('main', w2ui.user_grid);
        layout.set('right', {size: 450});
    }
};

export default Users