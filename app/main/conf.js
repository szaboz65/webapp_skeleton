// ============================================
// -- Application Configuration

export default {
    // --- Application  Layout
    app_layout: {
        name: 'app_layout',
        style: '',
        panels: [
            { type: 'top', size: '20px', overflow: 'hidden', hidden: true },
            { type: 'left', size: '180px', minSize: 100, resizable: true, style: 'border-right: 1px solid #ddd' },
            { type: 'main', overflow: 'hidden', style: 'background-color: white;' },
            { type: 'right', size: '400px', resizable: true, hidden: true, style: 'border-left: 1px solid #ddd' },
            { type: 'preview', size: '200px', overflow: 'hidden', hidden: true, resizable: true },
            { type: 'bottom', size: '40px', hidden: true }
        ]
    },

    // --- Application Top Toolbar (if any)
    app_toolbar: {
        name: 'app_toolbar',
        items: [
            { id: 'spacer1', type: 'spacer' },
            { id: 'swversion', type: 'html', html: '---' },
            { id: 'break1', type: 'break' },
            {
                id: 'help',
                text: 'Help',
                type: 'menu',
                icon: 'icon-question',
                items: [
                    { id: 'version', text: 'Version', icon: 'icon-wand' },
                    { id: 'release_notes', text: 'Release notes', icon: 'icon-list' }
                ]
            },
            {
                id: 'user',
                text: 'User Name',
                type: 'menu',
                items: [
                    { id: 'logout', text: 'Logout', icon: 'icon-off' }
                ]
            }
        ]
        /*,
        onClick(event) {

        } */
    },

    version_form: {
        fields: [
            {
                field: 'version',
                type: 'text',
                html: { label: 'Version', attr: 'style="width: 300px"' }
            },
            {
                field: 'builddate',
                type: 'text',
                html: { label: 'Build date', attr: 'style="width: 300px"' }
            },
            {
                field: 'apiversion',
                type: 'text',
                html: { label: 'API version', attr: 'style="width: 300px"' }
            },
            {
                field: 'apibuilddate',
                type: 'text',
                html: { label: 'API builddate', attr: 'style="width: 300px"' }
            }
        ]
    },

    version_popup: {
        title: 'Version info',
        width: 500,
        height: 300
    },

    release_notes_grid: {
        columns: [
            { field: 'version', text: 'Version', size: '100px' },
            { field: 'date', text: 'Date', size: '120px' },
            { field: 'notes', text: 'Notes', size: '100%' }
        ]
    },

    release_notes_popup: {
        title: 'Release Notes',
        width: 800,
        height: 600
    }

}
