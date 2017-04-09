CKEDITOR.plugins.add('rkhelpermodule', {
    requires: 'popup',
    lang: 'en,nl,de',
    init: function (editor) {
        editor.addCommand('insertRKHelperModule', {
            exec: function (editor) {
                var url = Routing.generate('rkhelpermodule_external_finder', { objectType: 'linker', editor: 'ckeditor' });
                // call method in RKHelperModule.Finder.js and provide current editor
                RKHelperModuleFinderCKEditor(editor, url);
            }
        });
        editor.ui.addButton('rkhelpermodule', {
            label: editor.lang.rkhelpermodule.title,
            command: 'insertRKHelperModule',
            icon: this.path.replace('docs/scribite/plugins/CKEditor/vendor/ckeditor/plugins/rkhelpermodule', 'public/images') + 'admin.png'
        });
    }
});
