$(function () {
    var SiteTable = /** @class */ (function () {
        function SiteTable() {
            this.inSite = '';
            this.tempLi = '';
        }
        SiteTable.prototype.createNewLi = function () {
        };
        return SiteTable;
    }());
    var ul = $('.site_list');
    var $cloneTemplate = $('.list-group-item.clone');
    $('#btnGetSite').click(function () {
        var inSiteForm = $('.form-control').val();
        var $newListItem = $cloneTemplate.clone();
        $newListItem.removeClass('clone');
        $(".list-group-item-url", $newListItem).text(inSiteForm);
        $(".list-group-item-url", $newListItem).attr('href', 'https://' + inSiteForm);
        $("input", $newListItem).val(inSiteForm);
        $('.form-control').val('');
        ul.append($newListItem);
        makeEventsForListGroupItem($newListItem);
    });
    var makeEventsForListGroupItem = function ($domElem) {
        $('.delete-button', $domElem).click(function () {
            var listGroupItem = $(this).closest('.list-group-item');
            listGroupItem.remove();
        });
    };
    makeEventsForListGroupItem($('.list-group-item:visible'));
});
