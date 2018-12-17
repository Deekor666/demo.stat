<?php
/* Smarty version 3.1.34-dev-7, created on 2018-12-12 08:44:10
  from '/var/www/parser/public/tpl/main.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5c10ca5ae6b1b9_10708845',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '150563f9bf273a096b523abd896ec7ee2253c605' => 
    array (
      0 => '/var/www/parser/public/tpl/main.tpl',
      1 => 1544525225,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c10ca5ae6b1b9_10708845 (Smarty_Internal_Template $_smarty_tpl) {
?><html lang="en">
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
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['sites']->value, 'site');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['site']->value) {
?>
                    <li class='list-group-item'>
                        <input type='hidden' name='siteNames[]' value='<?php echo $_smarty_tpl->tpl_vars['site']->value->url;?>
'>
                        <a class='list-group-item-item' target='_blank' href=''><?php echo $_smarty_tpl->tpl_vars['site']->value->url;?>
</a>
                        <button type='button' class='delete-button btn btn-outline-primary'>Удалить</button>
                    </li>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
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
<?php echo '<script'; ?>
 type="text/javascript" src="../scripts/loader.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="../scripts/jquery-3.3.1.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="../scripts/bootstrap.bundle.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="../scripts/moment.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="../scripts/daterangepicker.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>

    let siteData = '<?php echo $_smarty_tpl->tpl_vars['siteData']->value;?>
';
    let siteDatas = JSON.parse(siteData);

    console.log(siteDatas);

    let error = '<?php echo $_smarty_tpl->tpl_vars['error']->value;?>
';
    if (typeof error === "string" && error !== '') {
        $('.for_error').append('<div class="alert alert-danger" role="alert">\n' +
            error +
            '</div>');
    }

    let siteErrors = '<?php echo $_smarty_tpl->tpl_vars['siteErrors']->value;?>
';
    let siteErrorsPars = JSON.parse(siteErrors);

    if (typeof siteErrorsPars === 'object') {
        let siteError = siteErrorsPars.join(', ');
        $('.for_error').append(
            '<div class="alert alert-danger" role="alert">\n' + 'Невозможно выбрать статистику:' + ' ' + siteError +
            '</div>');


    }

    


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
            crosshair: {color: '#000', trigger: 'selection'}
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

    
<?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 src="../scripts/script.js"><?php echo '</script'; ?>
>
</body>
</html><?php }
}
