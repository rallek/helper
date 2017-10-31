/**
 * Initializes the plugin, this will be executed after the plugin has been created.
 * This call is done before the editor instance has finished it's initialization so use the onInit event
 * of the editor instance to intercept that event.
 *
 * @param {tinymce.Editor} ed Editor instance that the plugin is initialised in
 * @param {string} url Absolute URL to where the plugin is located
 */
tinymce.PluginManager.add('rkhelpermodule', function(editor, url) {
    var icon;

    icon = Zikula.Config.baseURL + Zikula.Config.baseURI + '/web/modules/rkhelper/images/admin.png';

    editor.addButton('rkhelpermodule', {
        //text: 'Helper',
        image: icon,
        onclick: function() {
            RKHelperModuleFinderOpenPopup(editor, 'tinymce');
        }
    });
    editor.addMenuItem('rkhelpermodule', {
        text: 'Helper',
        context: 'tools',
        image: icon,
        onclick: function() {
            RKHelperModuleFinderOpenPopup(editor, 'tinymce');
        }
    });

    return {
        getMetadata: function() {
            return {
                title: 'Helper',
                url: 'http://k62.de'
            };
        }
    };
});
