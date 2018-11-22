$(function () {

    let ul = $('.site_list');
    let $cloneTemplate = $('.list-group-item.clone');
    $('#btnGetSite').click(function () {
        let inSiteForm = $('.form-control').val();
        if (inSiteForm !== '') {
            let $newListItem = $cloneTemplate.clone();
            $newListItem.removeClass('clone');
            $(".list-group-item-url", $newListItem).text(inSiteForm);
            $(".list-group-item-url", $newListItem).attr('href', 'https://' + inSiteForm);
            $("input", $newListItem).val(inSiteForm);
            $('.form-control').val('');
            ul.append($newListItem);
            makeEventsForListGroupItem($newListItem);
        }
    });
    let makeEventsForListGroupItem = function ($domElem) {
        $('.delete-button', $domElem).click(function () {
            let listGroupItem = $(this).closest('.list-group-item');
            listGroupItem.remove();
        });
    };
    makeEventsForListGroupItem($('.list-group-item:visible'));
    /**
     *  Календарь
     */

    let start = moment().subtract(29, 'days');
    let end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('DD.MM.YYYY') + ' - ' + end.format('DD.MM.YYYY'));
    }

    $('#reportrange').daterangepicker({
        opens: 'center',
        startDate: start,
        endDate: end,
        locale: {
            format: 'DD.MM.YYYY'
        },
        minYear: 2018,
        maxYear: parseInt(moment().format('YYYY'), 10),
        ranges: {
            'Неделя': [moment().subtract(7, 'day'), moment()],
            'Месяц': [moment().subtract(1, 'month'), moment()],
            'Квартал': [moment().subtract(3, 'month'), moment()],
            'Пол года': [moment().subtract(6, 'month'), moment()],
            'Год': [moment().subtract(12, 'month'), moment()],
            // 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    $('input[name="period"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD.MM.YYYY') + ' - ' + picker.endDate.format('DD.MM.YYYY'));
    });

    $('input[name="period"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });


});




