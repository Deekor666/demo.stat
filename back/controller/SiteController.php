<?php

require_once '../back/model/Site.php';

class SiteController
{

    private $_smarty;

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


        $data = $_GET;

        if (!empty($data['siteNames'])) {
            foreach ($data['siteNames'] as $siteName) {
                if (!empty($siteName)) {
                    $sitesList[] = $siteName;
                }
            }
        }
        var_dump($sitesList);
        foreach ($sitesList as $item) {
            $findHttp = preg_match('|^http?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $item);
            $findHttps = preg_match('|^https?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $item);
            if ($findHttp === 1 && $findHttps === 0){
                var_dump(mb_strcut($item, 0, 7));
            } else {
                var_dump('1234');
            }
        }

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

}