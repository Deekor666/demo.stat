<?php

var_dump($_GET);
?>

<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <link rel="stylesheet" href="/styles/bootstrap.min.css"/>
    <link rel="stylesheet" href="/styles/style.css"/>
    <title>Счетчик</title></head>
<body>
<div class="container"><h1 class="first-title">Статистика от LiveInternet в динамике</h1>
    <form class="my-form" method="GET">
        <div class="sites">
            <div class="add-del-site">
                <div class="input-group mb-3">
                    <div class="input-group-prepend"><span class="input-group-text" id="basic-addon3">https://</span>
                    </div>
                    <input class="form-control" value="" type="text" id="basic-url"/></div>
                <div class="buttons">
                    <button id="btnGetSite" class="btn btn-outline-primary" type="button">Добавить</button>
                </div>
            </div>
            <ul class="site_list list-group">
                <li class="clone list-group-item">
                    <a class="list-group-item-url" href="">lorem</a>
                    <button type="button" class="delete-button btn btn-outline-primary">Удалить</button>
                </li>
            </ul>
        </div>
        <div class="options"><span class="opt-text">Показать динамику количества</span>
            <div class="dropdown">
                <button class=" btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">поситителей
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li class="dropdown-item">визитов</li>
                    <li class="dropdown-item">визитов на поситителей</li>
                </ul>
            </div>
            <span class="opt-text">по</span>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">дням
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"><a class="dropdown-item" href="#">неделям</a><a
                        class="dropdown-item" href="#">месяцам</a></div>
            </div>
            <button class="btn btn-outline-primary" type="submit">посмотреть</button>
        </div>
    </form>
</div>
<script src="scripts/jquery-3.3.1.min.js"></script>
<script src="scripts/script.js"></script>
</body>
</html>

