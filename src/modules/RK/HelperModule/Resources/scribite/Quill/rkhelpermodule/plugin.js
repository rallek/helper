var rkhelpermodule = function(quill, options) {
    setTimeout(function() {
        var button;

        button = jQuery('button[value=rkhelpermodule]');

        button
            .css('background', 'url(' + Zikula.Config.baseURL + Zikula.Config.baseURI + '/web/modules/rkhelper/images/admin.png) no-repeat center center transparent')
            .css('background-size', '16px 16px')
            .attr('title', 'Helper')
        ;

        button.click(function() {
            RKHelperModuleFinderOpenPopup(quill, 'quill');
        });
    }, 1000);
};
