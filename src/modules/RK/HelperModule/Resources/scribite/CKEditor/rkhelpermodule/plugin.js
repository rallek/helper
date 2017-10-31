CKEDITOR.plugins.add('rkhelpermodule', {
    requires: 'popup',
    init: function (editor) {
        editor.addCommand('insertRKHelperModule', {
            exec: function (editor) {
                RKHelperModuleFinderOpenPopup(editor, 'ckeditor');
            }
        });
        editor.ui.addButton('rkhelpermodule', {
            label: 'Helper',
            command: 'insertRKHelperModule',
            icon: this.path.replace('scribite/CKEditor/rkhelpermodule', 'images') + 'admin.png'
        });
    }
});
