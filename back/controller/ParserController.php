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
        $sites = Site::getSites();
        $parser = new Parser();
        foreach ($sites as $site) {
            var_dump($site);
            $site->getSiteData(); //заполнили старые данные
            $parser->loadSiteData($site); //загружаем и сохраняем новые
        }

        echo 'ok';
    }

    /**
     * запуск парсера
     */
    public static function loadSiteData($site)
    {
        $parser = new Parser();
        $parser->loadSiteData($site);
    }

}