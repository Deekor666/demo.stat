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
    $(".list-group-item-url", $newListItem).text(inSiteForm);
    ul.append($newListItem);
    console.log(inSiteForm);
});
