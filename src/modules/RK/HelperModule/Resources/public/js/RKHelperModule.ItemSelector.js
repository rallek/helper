'use strict';

var rKHelperModule = {};

rKHelperModule.itemSelector = {};
rKHelperModule.itemSelector.items = {};
rKHelperModule.itemSelector.baseId = 0;
rKHelperModule.itemSelector.selectedId = 0;

rKHelperModule.itemSelector.onLoad = function (baseId, selectedId)
{
    rKHelperModule.itemSelector.baseId = baseId;
    rKHelperModule.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    jQuery('#rKHelperModuleObjectType').change(rKHelperModule.itemSelector.onParamChanged);

    jQuery('#' + baseId + '_catidMain').change(rKHelperModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + '_catidsMain').change(rKHelperModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'Id').change(rKHelperModule.itemSelector.onItemChanged);
    jQuery('#' + baseId + 'Sort').change(rKHelperModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'SortDir').change(rKHelperModule.itemSelector.onParamChanged);
    jQuery('#rKHelperModuleSearchGo').click(rKHelperModule.itemSelector.onParamChanged);
    jQuery('#rKHelperModuleSearchGo').keypress(rKHelperModule.itemSelector.onParamChanged);

    rKHelperModule.itemSelector.getItemList();
};

rKHelperModule.itemSelector.onParamChanged = function ()
{
    jQuery('#ajaxIndicator').removeClass('hidden');

    rKHelperModule.itemSelector.getItemList();
};

rKHelperModule.itemSelector.getItemList = function ()
{
    var baseId;
    var params;

    baseId = rKHelperModule.itemSelector.baseId;
    params = {
        ot: baseId,
        sort: jQuery('#' + baseId + 'Sort').val(),
        sortdir: jQuery('#' + baseId + 'SortDir').val(),
        q: jQuery('#' + baseId + 'SearchTerm').val()
    }
    if (jQuery('#' + baseId + '_catidMain').length > 0) {
        params[catidMain] = jQuery('#' + baseId + '_catidMain').val();
    } else if (jQuery('#' + baseId + '_catidsMain').length > 0) {
        params[catidsMain] = jQuery('#' + baseId + '_catidsMain').val();
    }

    jQuery.getJSON(Routing.generate('rkhelpermodule_ajax_getitemlistfinder'), params, function( data ) {
        var baseId;

        baseId = rKHelperModule.itemSelector.baseId;
        rKHelperModule.itemSelector.items[baseId] = data;
        jQuery('#ajaxIndicator').addClass('hidden');
        rKHelperModule.itemSelector.updateItemDropdownEntries();
        rKHelperModule.itemSelector.updatePreview();
    });
};

rKHelperModule.itemSelector.updateItemDropdownEntries = function ()
{
    var baseId, itemSelector, items, i, item;

    baseId = rKHelperModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id');
    itemSelector.length = 0;

    items = rKHelperModule.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.get(0).options[i] = new Option(item.title, item.id, false);
    }

    if (rKHelperModule.itemSelector.selectedId > 0) {
        jQuery('#' + baseId + 'Id').val(rKHelperModule.itemSelector.selectedId);
    }
};

rKHelperModule.itemSelector.updatePreview = function ()
{
    var baseId, items, selectedElement, i;

    baseId = rKHelperModule.itemSelector.baseId;
    items = rKHelperModule.itemSelector.items[baseId];

    jQuery('#' + baseId + 'PreviewContainer').addClass('hidden');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (rKHelperModule.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id == rKHelperModule.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (null !== selectedElement) {
        jQuery('#' + baseId + 'PreviewContainer')
            .html(window.atob(selectedElement.previewInfo))
            .removeClass('hidden');
        rKHelperInitImageViewer();
    }
};

rKHelperModule.itemSelector.onItemChanged = function ()
{
    var baseId, itemSelector, preview;

    baseId = rKHelperModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id').get(0);
    preview = window.atob(rKHelperModule.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    jQuery('#' + baseId + 'PreviewContainer').html(preview);
    rKHelperModule.itemSelector.selectedId = jQuery('#' + baseId + 'Id').val();
    rKHelperInitImageViewer();
};

jQuery(document).ready(function() {
    var infoElem;

    infoElem = jQuery('#itemSelectorInfo');
    if (infoElem.length == 0) {
        return;
    }

    rKHelperModule.itemSelector.onLoad(infoElem.data('base-id'), infoElem.data('selected-id'));
});
