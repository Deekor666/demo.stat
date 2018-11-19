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
let makeEventsForListGroupItem = function ($domElem) {
    $('.delete-button', $domElem).click(function () {
        let listGroupItem = $(this).closest('.list-group-item');
        listGroupItem.remove();
    });
};
makeEventsForListGroupItem($('.list-group-item:visible'));
/**
 * График:
 */
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawLineColors);

function drawLineColors() {
    let data = new google.visualization.DataTable();
    data.addColumn('number', 'Num');
    data.addColumn('number', '1');
    data.addColumn('number', '2');

    data.addRows([
        [100, 100, 456], [150, 150, 345], [155, 155, 234], [200, 200, 234], [250, 250, 234], [300, 300, 456]

    ]);

    let options = {
        'legend': 'left',
        'is3D': true,
        'width': 1000,
        'height': 300,
        hAxis: {
            title: 'Time'
        },
        curveType: 'function',
        vAxis: {
            title: 'Popularity'
        },
        backgroundColor: '#fff',
        colors: ['red', 'green']
    };

    let chart = new google.visualization.LineChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}
