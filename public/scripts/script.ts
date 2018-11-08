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
     $(".list-group-item-url", $newListItem).text(inSiteForm);
    ul.append($newListItem);
    console.log(inSiteForm);
});

