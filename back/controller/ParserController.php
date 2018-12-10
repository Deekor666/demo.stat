<?php

require_once(BACK_DIR . 'model/Parser.php');

class ParserController{

    private $_smarty;

    public function __construct()
    {
        $this->_smarty = General::getSmarty();
    }

    public static function loadSitesData()
    {
        $sites = Site::getSites(true);
        $parser = new Parser();
        foreach ($sites as $site) {
            $parser->loadAltSiteData($site);
        }

        echo 'ok';
    }

    /**
     * запуск парсера
     *
     * @param $site
     */
    public static function loadSiteData($site)
    {
        $parser = new Parser();
        $parser->loadAltSiteData($site);

    }

}