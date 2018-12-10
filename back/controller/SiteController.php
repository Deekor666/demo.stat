<?php

require_once(BACK_DIR . 'model/Site.php');

class SiteController
{

    private $_smarty;

    /**
     * SiteController constructor.
     * в конструкте запуск функции actionIndex
     *
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
        } else {
            echo 'Action error';
        }
    }


    /**
     * получение формы
     *
     * маршрутизация данных
     */

    public function actionIndex()
    {
        $sitesList = [];
        $data = $_GET;
        if (!empty($data['siteNames'])) {
            foreach ($data['siteNames'] as $siteName) {
                if (!empty($siteName)) {
                    $sitesList[] = $siteName;
                }
            }
        }
        $uniqueValidSites = $this->validation($sitesList);
        $optionsArray = $this->inputOptions($data);
        $sitesAndErrors = $this->createNewSites($uniqueValidSites);
        $sortResultData = $this->formationData($sitesAndErrors['sites'], $optionsArray);

        if ($optionsArray['time'] === 'week') {
            $sortResultData = $this->weekDateSort($sortResultData);
        } elseif ($optionsArray['time'] === 'month') {
            $sortResultData = $this->monthDateSort($sortResultData);
        }
        $resultData = $this->makeDataForChart($sortResultData);
        $this->sendDataInIndex($resultData, $sitesAndErrors);

    }

    /**
     * Валидация сайтов
     * @param $sitesList
     * @return array
     */
    public function validation($sitesList)
    {
        $findHttps = "/https?:\/\//mi";
        $findDomainZone = "/.com|.ru|.net|.org|.рф|.do/mi";
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
        return $uniqueValidSites;
    }


    /**
     * Создание объектов сайтов
     * Проверка работы liveInternet и прихода данных сайта ----------->Вынести отдельно?
     *
     * @param array $uniqueValidSites
     * @return array ['sites' => $sites, 'siteErrors' => $siteErrors, 'error' => $error]
     */
    public function createNewSites($uniqueValidSites)
    {
        $error = false;
        $siteErrors = false;
        $sites = [];
        $pingState = General::pingTest();
        foreach ($uniqueValidSites as $item) {
            $site = new Site($item);
            if ($pingState === 1) {
                $site->getSiteData();
                ParserController::loadSiteData($site);
                $site->getSiteData();
            } else {
                $error = "На данный момент, сервер статистики недоступен";
            }
            if ($site->st == 1 && !empty($site->data)) {
                $sites[] = $site;
            } else {
                $siteErrors[] = $site->url;
            }
        }
        $sitesAndErrors = ['sites' => $sites, 'siteErrors' => $siteErrors, 'error' => $error];
        return $sitesAndErrors;
    }

    /**
     * получаем и преобразовываем данные из инпутов формы
     *
     * @param $data
     * @return array
     */
    public function inputOptions($data)
    {
        $monthTimeStamp = 2629743;
        $prosmotrArray = ['prosmotr', 'posetit'];
        $timeArray = ['day', 'week', 'month'];
        if (!empty($data['prosmotr']) && in_array($data['prosmotr'], $prosmotrArray)) {
            $prosmotr = $data['prosmotr'];
        } else {
            $prosmotr = 'posetit';
        }
        if (!empty($data['time']) && in_array($data['time'], $timeArray)) {
            $time = $data['time'];
        } else {
            $time = 'day';
        }
        if (!empty($data['period'])) {
            $period = $data['period'];
            $period = explode('-', $period);
            $dateStartTimestamp = strtotime($period[0]);
            $dateEndTimestamp = strtotime($period[1]);
        } else {
            if ($time === 'month') {
                $dateEndTimestamp = strtotime(date("Y-m-d"));
                $dateStartTimestamp = $dateEndTimestamp - ($monthTimeStamp * 6);
            } else if ($time === 'week') {
                $dateEndTimestamp = strtotime(date("Y-m-d"));
                $dateStartTimestamp = $dateEndTimestamp - ($monthTimeStamp * 3);
            } else {
                $dateEndTimestamp = strtotime(date("Y-m-d"));
                $dateStartTimestamp = $dateEndTimestamp - $monthTimeStamp;
            }
        }
        $optionsArray = ['dateStartTimestamp' => $dateStartTimestamp, 'dateEndTimestamp' => $dateEndTimestamp, 'time' => $time, 'prosmotr' => $prosmotr];
        return $optionsArray;
    }

    /**
     * Формирование данных в удобной форме
     *
     * @param $sites
     * @param $options
     * @return array [url1 => ['xx.xx.xx' => 123, 'yy.yy.yy' => 345], url2 => ['xx.xx.xx' => 123, 'yy.yy.yy' => 345]]
     */
    public function formationData($sites, $options)
    {
        $res = [];
        $i = 0;
        $currentTimestamp = $options['dateStartTimestamp'];
        $resultData = [];
        while ($currentTimestamp <= $options['dateEndTimestamp']) {
            $day = date("Y-m-d", $currentTimestamp);
            foreach ($sites as $site) {
                $url = $site->url;
                if (!empty($site->data[$day])) {
                    $res[$url][$day] = $site->data[$day][$options['prosmotr']];
                } else {
                    $res[$url][$day] = 0;
                }
                $resultData = $res;
            }
            $i++;
            $currentTimestamp = $options['dateStartTimestamp'] + $i * 86400;
        }

        return $resultData;
    }

    /**
     *
     * [['Date', 'Site1', 'Site2'],
     * ['2004', 1000, 400],
     * ['2005',  1170, 460],
     * ['2006',  660, 1120],
     * ['2007',  1030, 540]]
     * @param $data
     * @return array
     */

    public function makeDataForChart($data)
    {
        $resultData = [];
        $i = 1;
        $firstStrArray = ['Date'];
        foreach ($data as $url => $dates) {
            $firstStrArray[] = $url;
        }
        $resultData[] = $firstStrArray;
        foreach ($data as $site) {
            foreach ($site as $date => $value) {
                if (empty($resultData[$i])) {
                    $resultData[$i] = [$date, $value];
                } else {
                    $resultData[$i][] = $value;
                }
                $i++;
            }
            $i = 1;
        }
        return $resultData;
    }

    public function weekDateSort($data)
    {
        $dateStart = '';
        $sum = 0;
        $resultData = [];
        $i = 0;

        foreach ($data as $url => $site) {
            foreach ($site as $date => $value) {
                if ($i === 0) {
                    $dateStart = $date;
                    $sum += $value;
                    $i++;
                } else if ($i > 0 && $i < 7) {
                    $sum += $value;
                    $i++;
                } else if ($i === 7) {
                    $resultData[$url][$dateStart] = $sum;
                    $dateStart = $date;
                    $i = 1;
                    $sum = 0;
                }
            }
            $resultData[$url][$dateStart] = $sum;
            $i = 0;
            $sum = 0;
        }

        return $resultData;
    }

    public function monthDateSort($data)
    {
        $dateStart = '';
        $sum = 0;
        $resultData = [];
        $currentMonth = '';
        foreach ($data as $url => $site) {
            foreach ($site as $date => $value) {
                $month = date('M', strtotime($date));
                if (empty($currentMonth)) {
                    $currentMonth = $month;
                    $dateStart = $date;
                }
                if ($month != $currentMonth) {
                    $resultData[$url][$dateStart] = $sum;
                    $dateStart = $date;
                    $sum = 0;
                    $currentMonth = $month;
                } else {
                    $sum += $value;
                }
            }
            $resultData[$url][$dateStart] = $sum;
        }
        var_dump($resultData);
        return $resultData;
    }

    /**
     * Отправка данных в шаблон
     *
     * @param $resultData
     * @param $sitesAndErrors
     */
    public function sendDataInIndex($resultData, $sitesAndErrors)
    {
        $this->_smarty->assign('siteData', json_encode($resultData));

        $this->_smarty->assign('sites', $sitesAndErrors['sites']);
        $this->_smarty->assign('error', $sitesAndErrors['error']);
        $this->_smarty->assign('siteErrors', json_encode($sitesAndErrors['siteErrors']));
        $this->_smarty->display('main.tpl');
    }

}
