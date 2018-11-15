<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <link rel="stylesheet" href="../styles/bootstrap.min.css"/>
    <link rel="stylesheet" href="../styles/style.css"/>
    <title>Счетчик</title></head>
<body>
<div class="container"><h1 class="first-title">Статистика от LiveInternet в динамике</h1>
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
                foreach ($sites as $key => $val) {
                    ?>
                    <li class='list-group-item'>
                        <input type='hidden' name='siteNames[]' value='<?= $val ?>'>
                        <a class='list-group-item-item' target='_blank' href=''> <?= $val ?> </a>
                        <button type='button' class='delete-button btn btn-outline-primary'>Удалить</button>
                    </li>
                <?php
                }
                ?>

            </ul>
        </div>
        <div class="options"><span class="opt-text">Показать динамику количества</span>
            <select class="custom-select" name="prosmotr">
                <option value="posetit">Посетителей</option>
                <option value="prosmotr">визитов</option>
                <option value="prosmotrPosetit">визитов на посетителей</option>
            </select>
            <span class="opt-text">по</span>
            <select class="custom-select" name="time">
                <option value="day">дням</option>
                <option value="week">неделям</option>
                <option value="month">месяцам</option>
            </select>
            <button id="submit" class="btn btn-outline-primary" type="submit">посмотреть</button>
        </div>
    </form>
</div>
<script src="../scripts/jquery-3.3.1.min.js"></script>
<script src="../scripts/bootstrap.bundle.min.js"></script>
<script src="../scripts/script.js"></script>
</body>
</html>
