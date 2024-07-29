// 
// ============================================
// -- Configuration for the users module

export default {
    user_grid: {
        style: 'border: 0px',
        sortData: [{field: 'userid', direction: 'asc'}],
        searches: [
            {field: 'userid', type: 'int', label: 'ID'},
            {field: 'name', type: 'text', label: 'Name'},
            {field: 'title', type: 'text', label: 'Title'},
            {field: 'phone', type: 'text', label: 'Phone'},
            {field: 'email', type: 'text', label: 'Email'},
            {field: 'fk_utypeid', type: 'list', label: 'Usertype',
                options: {items: []}},
            {field: 'inactive', type: 'select', label: 'Inactive',
                options: {items: []}, style: 'width: 100px;'},
            {field: 'roles', type: 'enum', label: 'Has Role',
                options: {items: []}}
        ],
        columns: [
            {field: 'userid', text: 'ID', size: '60px', sortable: true},
            {field: 'name', text: 'Name', size: '100%', sortable: true, min:'120px'},
            {field: 'title', text: 'Title', size: '200px', sortable: true},
            {field: 'phone', text: 'Phone', size: '120px', sortable: true},
            {field: 'email', text: 'Email', size: '200px', sortable: true},
            {field: 'utypename', text: 'Usertype', size: '120px', sortable: true},
            {field: 'inactive', text: 'Inactive', size: '64px', sortable: true,
                render: function (record) {
                    return record && parseInt(record.inactive) === 1 ? 'Yes' : '';
                }
            }
        ]
    },

    user_form: {
        fields: [
            {field: 'name', type: 'text', required: true,
                html: {label: 'Name', attr: 'maxlength="64" style="width: 300px"'}},
            {field: 'title', type: 'text', 
                html: {label: 'Title', attr: 'maxlength="32" style="width: 300px"'}},
            {field: 'phone', type: 'text', 
                html: {label: 'Phone', attr: 'maxlength="32" style="width: 300px"'}},
            {field: 'email', type: 'email', required: true,
                html: {label: 'Email', attr: 'maxlength="128" style="width: 300px"'}},
            {field: 'fk_utypeid', type: 'list', required: true,
                html: {label: 'UserType', attr: 'style="width: 300px"'}},
            {field: 'inactive', type: 'list', required: true,
                html: {label: 'Inactive'}}
        ]
    },

    user_popup: {
        title: 'User',
        width: 680,
        height: 420
    },
    
    user_view_css: `
.user-main{margin-top:30px;padding:10px;}
.user-col1{float:left;width:160px;padding:5px;text-align:center}
.user-col1 .user-photo{width:120px;height:135px;margin:0 auto;border-radius:3px;background-color:#888;overflow:hidden;border:1px solid #888}
.user-col1 .user-photo img{width:120px;height:135px;border:0}
.user-col2{position:relative;margin-left:160px;padding:5px}
.user-col2 .user-name{display:block;font-size:20px;padding:5px 0}
.user-col2 .user-email{display:block;padding:3px}
.user-col2 .user-teamname{display:block;padding:3px}
.user-col3{margin-top:20px}
.user-col3 .user-details{padding-top:10px!important;line-height:150%}
.w2ui-field>label{clear:right}`,

    user_view_html: `
<div class="user-main">
    <div class="user-col1">
        <div class="user-photo">
            <img id="user-photo">
        </div>
    </div>
    <div class="user-col2">
        <span id="name" class="user-name w2ui-group-title"></span>
        <span id="email" class="user-email">&nbsp;</span>
        <div style="height: 20px;"></div>
    </div>
    <div style="clear:both;"></div>
    <hr>
    <h2>Details</h2>
    <div id="details" class="user-col3">
    </div>
    <hr>
    <h2>Activity</h2>
    <div id="activity" class="user-col3">
    </div>
    <hr>
</div>`,
        
    close_box_html: `<div><div id="user_close" style="float:right;padding:10px;"><span class="w2ui-icon icon-cross"></span></div></div>`,

    msg_noselected: "Select an item to view!",
    msg_manyselected: "Select only one item!",
    preview_msg_html: '<div class="preview-msg">$message</div>'
    
};