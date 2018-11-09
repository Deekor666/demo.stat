$(function () {

    class SiteTable {
        public inSite: any = '';
        public tempLi: string = '';

        createNewLi(){

        }
    }



    let ul = $('.site_list');
    let $cloneTemplate = $('.list-group-item.clone');

    $('#btnGetSite').click(function () {
        let inSiteForm = $('.form-control').val();
        let $newListItem = $cloneTemplate.clone();
        $newListItem.removeClass('clone');
        $(".list-group-item-url", $newListItem).text(inSiteForm);
        $(".list-group-item-url", $newListItem).attr('href', 'https://' + inSiteForm);
        $("input", $newListItem).val(inSiteForm);
        $('.form-control').val('');

        ul.append($newListItem);
        makeEventsForListGroupItem($newListItem);
    });

    let makeEventsForListGroupItem  = function($domElem) {
        $('.delete-button', $domElem).click(function() {
            let listGroupItem = $(this).closest('.list-group-item');
            listGroupItem.remove();
        });
    };

    makeEventsForListGroupItem($('.list-group-item:visible'));



});