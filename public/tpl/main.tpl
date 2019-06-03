<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>


    <link rel="stylesheet" href="../styles/bootstrap.min.css"/>
    <link rel="stylesheet" href="../styles/daterangepicker.css"/>
    <link rel="stylesheet" href="../styles/style.css"/>
    <title>Счетчик</title></head>
<body>
<div class="container"><h1 class="first-title">Статистика от LiveInternet в динамике</h1>
    <div class="for_error"></div>
    <form id="form" class="my-form" method="GET" action="">
        <div class="sites">
            <div class="add-del-site">
                <div class="input-group mb-3">
                    <div class="input-group-prepend"><span class="input-group-text" id="basic-addon3">http://</span>
                    </div>
                    <input class="form-control" value="" type="text" id="basic-url"/></div>
                <div class="buttons">
                    <button id="btnGetSite" class="btn btn-outline-primary" type="button">Добавить</button>
                </div>
            </div>
            <ul class="site_list list-group">
                <li class="clone list-group-item">
                    <input type="hidden" name="siteNames[]" value="">
                    <a class="list-group-item-url" target="_blank" href="">lorem</a>
                    <button type="button" class="delete-button btn btn-outline-primary">Удалить</button>
                </li>
                {foreach $sites as $site}
                    <li class='list-group-item'>
                        <input type='hidden' name='siteNames[]' value='{$site->url}'>
                        <a class='list-group-item-item' target='_blank' href=''>{$site->url}</a>
                        <button type='button' class='delete-button btn btn-outline-primary'>Удалить</button>
                    </li>
                {/foreach}
            </ul>
        </div>
        <div class="options"><span class="opt-text">Показать динамику количества</span>
            <select class="custom-select" name="prosmotr">
                <option value="posetit">Посетителей</option>
                <option value="prosmotr">визитов</option>
            </select>
            <span class="opt-text">по</span>
            <select class="custom-select" name="time">
                <option value="day">дням</option>
                <option value="week">неделям</option>
                <option value="month">месяцам</option>
            </select>
            <button id="submit" class="btn btn-outline-primary" type="submit">посмотреть</button>
        </div>
        <div class="period">
            <span>Период</span>
            <input id="reportrange" autocomplete="off" name="period" class="form-control">
        </div>
    </form>
    <div id="chart_div"></div>
    <div id="chart"></div>

</div>
<script type="text/javascript" src="../scripts/loader.js"></script>
<script src="../scripts/jquery-3.3.1.min.js"></script>
<script src="../scripts/bootstrap.bundle.min.js"></script>
<script src="../scripts/moment.min.js"></script>
<script src="../scripts/daterangepicker.min.js"></script>
<script>

    let siteData = '{$siteData}';
    let siteDatas = JSON.parse(siteData);

    let error = '{$error}';
    if (typeof error === "string" && error !== '') {
        $('.for_error').append('<div class="alert alert-danger" role="alert">\n' +
            error +
            '</div>');
    }

    let siteErrors = '{$siteErrors}';
    let siteErrorsPars = JSON.parse(siteErrors);

    if (typeof siteErrorsPars === 'object') {
        let siteError = siteErrorsPars.join(', ');
        $('.for_error').append(
            '<div class="alert alert-danger" role="alert">\n' + 'Невозможно выбрать статистику:' + ' ' + siteError +
            '</div>');


    }

    {literal}


    google.charts.load('current', {'packages': ['line']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        let data = google.visualization.arrayToDataTable(siteDatas);

        /**
         * Функция для выделения на графике точки, с появлением осей
         */
        function selectHandler() {
            let selectedItem = chart.getSelection()[0];
            if (selectedItem) {
                let topping = data.getValue(selectedItem.row, 0);
                alert('The user selected ' + topping);
            }
        }

        /**
         * Объект настроек
         */
        let options = {
            animation: {duration: 1000, easing: 'out',},
            vAxis: {scaleType: 'log', title: ' ', minValue: 0, maxValue: 1000},
            hAxis: {title: ' '},
            colors: ['#a52714', '#097138'],
            crosshair: {color: '#000', trigger: 'selection'},
            'height':500
        };

        /**
         * Подлючение действия "Выбор точки"
         */
        google.visualization.events.addListener(data, 'select', selectHandler);
        /**
         * Подключение к диву в html
         *
         */let chart = new google.charts.Line(document.getElementById('chart_div'));
        // let chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, google.charts.Line.convertOptions(options));
        // chart.draw(data, options);

    }

    {/literal}
</script>

<script src="../scripts/script.js"></script>
</body>
</html>
