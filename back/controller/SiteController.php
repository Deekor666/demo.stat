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

        if (isset($_GET)){
            $data = $_GET;
        }

        if (!empty($data['siteNames'])) {
            foreach ($data['siteNames'] as $siteName) {
                if (!empty($siteName)) {
                    $sitesList[] = $siteName;
                }
            }
        }
        $prosmotrArray = ['prosmotr', 'posetit', 'prosmotr-posetit'];
        $prosmotr = 'prosmotr';
        $timeArray = ['day', 'week', 'month'];
        $time = 'day';
        if (!empty($data['prosmotr']) && in_array( $data['prosmotr'], $prosmotrArray)) {
            $prosmotr = $data['prosmotr'];
        }
        if (!empty($data['time']) && in_array( $data['time'], $timeArray)) {
            $prosmotr = $data['prosmotr'];
        }
        $sites = [];

        foreach ($sitesList as $item) {
            $sites[] = new Site($item);
        }

        $this->_smarty->assign('sites', $sites);
        $this->_smarty->display('main.tpl');
    }

    public function actionParsing()
    {
        ParserController::getSitesData();
    }



}