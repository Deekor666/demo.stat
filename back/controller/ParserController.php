<?php

require_once '../back/model/Parser.php';
require_once '../back/model/Parser.php';


class ParserController{

    private $_smarty;

    public function __construct()
    {
        $this->_smarty = General::getSmarty();
    }

    public static function getSitesData()
    {
        $sites = Site::getSites();
        $parser = new Parser();
        foreach ($sites as $site) {
            $siteData = $parser->getSiteData($site);
            var_dump($site, $siteData);
        }
        echo 'ok';

    }
}