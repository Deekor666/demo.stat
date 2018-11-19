<?php

require_once '../back/model/Site.php';

class SiteController
{

    private $_smarty;

    /**
     * SiteController constructor.
     * в конструкте запуск функции actionIndex
     * или actionParsing
     */

    public function __construct()
    {
        $this->_smarty = General::getSmarty();
        $action = 'index';
        if (!empty($_GET['action'])) {
            $action = $_GET['action'];
        }
        if ($action === 'index') {
            $this->actionIndex();
        } else if ($action === 'parsing') {
            $this->actionParsing();
        } else {
            echo 'Action error';
        }
    }

    public function actionIndex()
    {
        $sitesList = [];

        /**
         * получение формы
         */

        $data = $_GET;

        if (!empty($data['siteNames'])) {
            foreach ($data['siteNames'] as $siteName) {
                if (!empty($siteName)) {
                    $sitesList[] = $siteName;
                }
            }
        }

        /**
         *
         * - определяешь есть ли http/s +
         * - если есть - обрезаем. Тебе не привыкать. Если нет - ну и +
         * - Дальше бьем оставшуюся строку по точкам. +
         * - обхявлем переменную строковую в которую будем лепить урл +
         * - Если первый элемент массива www - добавляем к строке
         * - если не www - добавляем прост элемент
         * - проходим массив по точками пока не встетим что-то вроде ru/com/net
         * - Обязательные условия для того, что признать урл валидным:
         *     -ru/com/net должен встретиться и присутствовать в строке
         *     -нужно чтобы тело урла содержало как минимум одну запись (не учитывая www)
         *
         */
        var_dump($sitesList);
        $findHttps = "/https?:\/\//mi";
        $findDomainZone = "/.com|.ru|.net|.org/mi";
        $matches = [];

        foreach ($sitesList as $item) {
            preg_match($findHttps, $item, $matches);
            if (!empty($matches)) {
                $item = str_replace($matches[0], '', $item);
            }
            preg_match($findDomainZone, $item, $match);
            if (!empty($match)) {
                $arraySiteExplode = explode('.', $item);


                foreach ($arraySiteExplode as $items) {
                    if (count($arraySiteExplode) > 1 || $arraySiteExplode[0] != 'www') {
                        $item = implode('.', $arraySiteExplode);
                    }
                }
                $resultValidSites[] = $item;
            }
        }
        var_dump($resultValidSites);

        $uniqueValidSites = array_unique($resultValidSites);
        var_dump($uniqueValidSites);

        /**
         * Разобраться, чего тут происходит и зачем ^:)
         *
         * Здесь собирается всякое дермище для заполнения инпутов формы
         */
        $prosmotrArray = ['prosmotr', 'posetit', 'prosmotr-posetit'];
        $prosmotr = 'prosmotr';
        $timeArray = ['day', 'week', 'month'];
        $time = 'day';
        if (!empty($data['prosmotr']) && in_array($data['prosmotr'], $prosmotrArray)) {
            $prosmotr = $data['prosmotr'];
        }
        if (!empty($data['time']) && in_array($data['time'], $timeArray)) {
            $prosmotr = $data['prosmotr'];
        }

        /**
         * Создание на основании полученного списка сайтов,
         * моделей сайтов.
         * Методом getSiteData() смотрим, есть ли уже такой сайт
         * если такого сайта нет, загружаем данные о новом сайте в бд
         *
         */
        $sites = [];
        foreach ($sitesList as $item) {
            $site = new Site($item);
            $site->getSiteData();
            if (empty($site->data)) {
                ParserController::loadSiteData($site);
                $site->getSiteData();
            }
            $sites[] = $site;
        }
        $this->_smarty->assign('sites', $sites);
        $this->_smarty->display('main.tpl');
    }

    public function actionParsing()
    {
        ParserController::loadSitesData();
    }

    public function sendDataChart()
    {

    }

}