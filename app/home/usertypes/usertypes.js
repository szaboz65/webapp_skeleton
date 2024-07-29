import { query, w2grid, w2utils, w2form, w2popup } from './../../libs/w2ui/w2ui.es6.js';
import W2Ajax from '../../main/w2/w2ajax.js';
import GridExt from '../../main/w2/gridext.js';
import Roles from '../../main/util/roles.js';
import conf from './conf.js';

class UserTypes {

    constructor() {
        // init grid
        this.usertype_grid = new w2grid(
        w2utils.extend({
            name: 'usertype_grid',
            url: app.getContextUrl('usertypes'),
            recid: 'utypeid',
            show: {
                toolbar: true,
                toolbarAdd: Roles.isAdmin(),
                toolbarEdit: Roles.isAdmin()
            },
            onAdd: function (event) {
                addUsertype();
            },
            onEdit: function (event) {
                addUsertype(event.detail.recid);
            },
            onDblClick: function (event) {
                if (Roles.isAdmin())
                    addUsertype(event.detail.recid);
            },
            onRender: function(event) {
                event.onComplete = function(ev) {
                    previewRoles();
                };
            },
            roles_rendering: false
        }, conf.usertype_grid));

        
        function previewRoles() {
            if (w2ui.usertype_grid.roles_rendering)
                return;
            w2ui.usertype_grid.roles_rendering = true;

            W2Ajax.getItems(app.getContextUrl('roleitems'), {}, function(items) {
                let layout = app.main.getLayout();
                layout.html('right', '<style>' + conf.view_css + '</style>' + conf.view_html);
                let $dsp = query(layout.el('right'));
                $dsp.find('#usertype-name').html('Available Roles');
                $dsp.find('#usertype-details').html(formDetail('Roles', formRoles(items)));
                w2ui.usertype_grid.roles_rendering = false;

                function formRoles(items) {
                    var roles = '';
                    for (var g in items)
                        roles += items[g].text + '<br>';
                    return roles;
                }
                function formDetail(caption, body) {
                    if (body === null || body === '')
                        body = '&nbsp;';
                    var html = '<div class="w2ui-field w2ui-span7">' +
                            '     <label>' + caption + '</label>' +
                            '    <div class="usertype-value">' + body + '</div>' +
                            '</div>';
                    return html;
                }
            });
        }

        function addUsertype(recid) {
            if (typeof w2ui.usertype_role_grid !== 'undefined') {
                w2ui.usertype_role_grid.destroy();
            }
            
            let usertype_role_grid = new w2grid(w2utils.extend({
                name: 'usertype_role_grid',
                recid: 'roleid'
            }, conf.usertype_role_grid));
            
            usertype_role_grid.load(app.getContextUrl('roles'), function () {
                let usertype_form = new w2form(w2utils.extend({
                    name: 'usertype_form',
                    url: app.getContextUrl('usertype', recid),
                    recid: recid,
                    actions: {
                        'Save': function () {
                            usertype_form.save({}, function (data) {
                                if (data?.error === true) {
                                    data?.details.forEach(e => {
                                        usertype_form.last.errors.push({field: usertype_form.get(e.field), error: e.message});
                                    });
                                    usertype_form.showErrors();
                                } else {
                                    GridExt.reload('usertype_grid', data, function () {
                                        w2popup.close();
                                    });
                                }
                            });
                        },
                        'Cancel': function () {
                            w2popup.close();
                        }
                    },
                    onLoad: function (event) {
                        event.onComplete = function () {
                            setTimeout(function() {
                                w2ui.usertype_role_grid.selectNone();
                                for( let i in w2ui.usertype_role_grid.records) {
                                    const role = w2ui.usertype_role_grid.records[i].roleid;
                                    if ((1<<(role-1)) & w2ui.usertype_form.record.roles) {
                                        w2ui.usertype_role_grid.select(role);
                                    }
                                }
                            },200);
                        };
                    },
                    onSubmit: function (event) {
                        event.detail.postData.record.roles = 0;
                        let sel = w2ui.usertype_role_grid.getSelection();
                        for (let i in sel) {
                            event.detail.postData.record.roles += (1<<(sel[i]-1));
                        }
                    }
                }, conf.usertype_form));

                w2popup.open(w2utils.extend({
                    title: conf.usertype_popup.title + ': ' + (!recid ? 'Add New' : 'Edit'),
                    body: '<div id="form" style="width: 100%; height: 100%;"></div>'
                }, conf.usertype_popup))
                .then(e => {
                    usertype_form.render(query('#w2ui-popup #form').get(0));
                    usertype_role_grid.render(query('#w2ui-popup #form #usertype_role_grid').get(0));
                })
                .close(e => {
                    usertype_form.destroy();
                    usertype_role_grid.destroy();
                });
            });
        }
    }
    
    start() {
        var layout = window.app.main.getLayout();
        layout.set('right', {size: 400});
        layout.html('main', w2ui.usertype_grid);
        layout.show('right', true);
    }
        
}

export default UserTypes
