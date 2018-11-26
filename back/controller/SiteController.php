<?php

require_once(BACK_DIR . 'model/Site.php');

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

    /**
     *
     */
    public function actionIndex()
    {
        $siteErrors = false;
        $error = false;
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
         * Валидация сайтов
         */
        $findHttps = "/https?:\/\//mi";
        $findDomainZone = "/.com|.ru|.net|.org|.рф/mi";
        $afterDomain = "/(\/.+)/mi";
        $matches = [];
        $resultValidSites = [];
        foreach ($sitesList as $item) {
            preg_match($findHttps, $item, $matches);
            if (!empty($matches)) {
                $item = str_replace($matches[0], '', $item);
            }
            preg_match($afterDomain, $item, $mat);
            if (!empty($mat)) {
                $item = str_replace($mat[0], '', $item);
            }
            preg_match($findDomainZone, $item, $match);
            if (!empty($match)) {
                $arraySiteExplode = explode('.', $item);
                foreach ($arraySiteExplode as $items) {
                    if (count($arraySiteExplode) > 1 && $arraySiteExplode[0] != 'www') {
                        $item = implode('.', $arraySiteExplode);
                    }
                }
                $resultValidSites[] = $item;
            }
        }

        $uniqueValidSites = array_unique($resultValidSites);
        /**
         * Здесь собирается всякое дерьмище для заполнения инпутов формы
         */
        $prosmotrArray = ['prosmotr', 'posetit'];
        $prosmotr = 'prosmotr';
        $timeArray = ['day', 'week', 'month'];
        $time = 'day';
        if (!empty($data['prosmotr']) && in_array($data['prosmotr'], $prosmotrArray)) {
            $prosmotr = $data['prosmotr'];
        }
        if (!empty($data['time']) && in_array($data['time'], $timeArray)) {
            $time = $data['time'];
        }
        /**
         * Проверяем работает ли лайвИнтернет,
         *
         * Создание на основании полученного списка сайтов,
         * моделей сайтов.
         * Методом getSiteData() смотрим, есть ли уже такой сайт
         * если такого сайта нет, загружаем данные о новом сайте в бд
         */
        $sites = [];
        foreach ($uniqueValidSites as $item) {
            $site = new Site($item);
            $pingState = General::pingTest();
            if ($pingState === 1) {
                $site->getSiteData();
                if (empty($site->data)) {
                    ParserController::loadSiteData($site);
                    $site->getSiteData();
                }
            } else {
                $error = "На данный момент, сервер статистики недоступен";
            }
            if ($site->st == 1 && !empty($site->data)) {
                $sites[] = $site;
            } else {
                $siteErrors[] = $site->url;
            }
        }

        /**
         * получаем и преобразовываем нужный период
         */

        if (!empty($data['period'])) {
            $period = $data['period'];
            $period = explode('-', $period);
            $dateStartTimestamp = strtotime($period[0]);
            $dateEndTimestamp = strtotime($period[1]);
        }


        /**
         * подготовка данных для отправки в график
         *
         * [['Year', 'Sales', 'Expenses'],
         * ['2004', 1000, 400],
         * ['2005',  1170, 460],
         * ['2006',  660, 1120],
         * ['2007',  1030, 540]]
         *
         * получение заглавной строки ['Year', 'Site1', 'Site1']
         *
         */
        if (isset($period)) {
            $firstStrInArray = ['Date'];
            foreach ($sites as $item) {
                $firstStrInArray[] = "$item->url";
            }
//var_dump($period);
            $i = 0;
            $currentTimestamp = $dateStartTimestamp;
            $daysArray = [];
            $resultData = [];
            while ($currentTimestamp <= $dateEndTimestamp) {
                $day = date("Y-m-d", $currentTimestamp);
                $res = [$day];
                $daysArray[] = $day;
                foreach ($sites as $site) {
                    if (!empty($site->data[$day])) {
//var_dump($site);
                        $test = $site->data[$day][$prosmotr]; // записываем в переменную тест количество просмотров
                        $res[] = $test; // в $res первым ключём идёт $day, а дальше записываем тест, с просмотрами и посещениями
                    } else {
                        $res[] = 0;
                    }
                }
                $i++;
                $currentTimestamp = $dateStartTimestamp + $i * 86400;

                $resultData[] = $res;

            }
            if ($time === 'week') {
                $this->weekDateSort($resultData);
//                    $currentTimestamp = $dateStartTimestamp + $i * 604800;
            } elseif ($time === 'month') {
                $this->monthDateSort($resultData);
//                    $currentTimestamp = $dateStartTimestamp + $i * 2629743;
            }

            /**
             * Склеиваемм массив с датой и данными для графика
             */
            array_unshift($resultData, $firstStrInArray);

            /**
             * Перобразовываем параметры, для отправки на страницу
             */
            $this->_smarty->assign('siteData', json_encode($resultData));

        }
        $this->_smarty->assign('sites', $sites);
        $this->_smarty->assign('error', $error);
        $this->_smarty->assign('siteErrors', json_encode($siteErrors));
        $this->_smarty->display('main.tpl');
    }

    public function actionParsing()
    {
        ParserController::loadSitesData();
    }

    public function weekDateSort($dates)
    {
        $weekDates = [];
        $weekDates = array_chunk($dates, 7, true);
//        var_dump($weekDates);
        foreach ($weekDates as $date) {
//            var_dump($date);
            if (count($date) <= 6) {
                for ($i = 0; $i <= count($date); $i++) {
                    $sum = $date[$i][1] + $date[$i + 1][1];
                }
            }
        }
        var_dump($sum);
    }

    public
    function monthDateSort($item)
    {
        var_dump($item);

    }

}
