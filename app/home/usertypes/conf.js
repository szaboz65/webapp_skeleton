// ============================================
// -- Configuration for the usertype module

export default {
    usertype_grid: {
        style: 'border: 0px',
        sortData: [{field: 'utypename', direction: 'asc'}],
        searches: [
            {field: 'utypeid', type: 'int', label: 'ID'},
            {field: 'utypename', type: 'text', label: 'Name'},
            {field: 'roles', type: 'enum', label: 'Has Role',
                options: {items: []}}
        ],
        columns: [
            {field: 'utypeid', text: 'ID', size: '60px', sortable: true, hidden: true},
            {field: 'utypename', text: 'Name', size: '150px', sortable: true},
            {field: 'roles', text: 'Roles', size: '100%', sortable: true,
                render: function(record) {
                    let html = '';
                    if (record && record.role) {
                        for( let i in record.role) {
                            if (i>0) html += ', ';
                            html += record.role[i].rolename;
                        }
                    }
                    return html;
                }
            }
        ]
    },

    usertype_form: {
        fields: [
            {field: 'utypename', type: 'text', required: true,
                html: {label: 'Name', attr: 'maxlength="64" style="width: 300px"'}},
            {field: 'roles', type: 'html',
                html: {label: 'Roles', html: '<div id="usertype_role_grid"></div>'}}
        ]
    },

    usertype_popup: {
        title: 'User Type',
        width: 500,
        height: 400
    },

    usertype_role_grid: {
        show: {
            columnHeaders: false,
            selectColumn: true
        },
        style: 'border: 0px; width:250px;height:224px',
        sortData: [{field: 'rolename', direction: 'asc'}],
        columns: [
            {field: 'roleid', label: 'ID', size: '60px', sortable: true, hidden: true},
            {field: 'rolename', label: 'Name', size: '100%', sortable: true}
        ]
    },
    
    view_css: `
.preview-usertype .usertype-title{height:40px;border-bottom:1px solid silver;padding:6px 2px}
.preview-usertype .usertype-title #usertype-name{margin-right:90px;padding:5px;font-size:24px}
.preview-usertype .usertype-details{height:152px;overflow:hidden;border-top:1px solid #888;border-bottom:1px solid silver;padding-top:10px}
.preview-usertype .usertype-details .usertype-value{padding:10px 5px 5px 0}
.preview-usertype .usertype-details #usertype-details{height:40px;overflow:hidden;text-overflow:ellipsis;margin-right:10px}`,
        
    view_html: `
<div class="preview-usertype w2ui-group-title">
    <div class="usertype-title">
        <div id="usertype-name"></div>
    </div>
    <div class="usertype-details" id="usertype-details">
    </div>
</div>`


};
