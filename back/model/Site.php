<?php

class Site
{

    public $id;
    public $url;
    public $dateCreate;
    public $dateGetData;
    public $data;
    public $st;
    public $error;

    private $_db;

    public function __construct($url = '')
    {
        $this->_db = General::getDb();

        if (!empty($url)) {
            $this->url = $url;
            $site = self::getSiteByUrl($url);
            $isNew = false;
            if (empty($site)) {
                $site = $this->save();
                $isNew = 1;
            }
            $this->id = $site['id'];
            $this->dateCreate = $site['date_create'];
            $this->dateGetData = $site['date_get_data'];
            $this->st = $site['st'];
            $this->error = $site['error'];
            if ($isNew) {
                ParserController::loadSiteData($this);
                $this->getSiteData();
            }
        }
    }

    public function save()
    {
        $stmt = $this->_db->prepare('INSERT INTO sites SET url = :url');
        $stmt->execute([':url' => $this->url]);
        $site = self::getSiteByUrl($this->url);
        return $site;
    }


    /**
     * Метод записывающий +1 ошибку, если данные сайта не пришли
     *
     * @param $site
     */
    public static function savePingStatementError($site)
    {
        $db = General::getDb();
        $stmt = $db->prepare('UPDATE sites SET error = error + 1 WHERE id = :id');
        $stmt->execute([':id' => $site->id]);
        Site::statementSiteOff($site);
    }


    /**
     * Метод обнуляющий ошибку, если данные сайта пришли
     *
     * @param $site
     */
    public static function resetPingStatementError($site)
    {
        $db = General::getDb();
        $stmt = $db->prepare('UPDATE sites SET error = 0 WHERE id = :id');
        $stmt->execute([':id' => $site->id]);
        Site::statementSiteOn($site);
    }


    /**
     * Метод изменяющий состояние сайта на вкл. изменением состояния на 1
     *
     * @param $site
     */
    public static function statementSiteOn($site)
    {
        $db = General::getDb();
        $stmt = $db->prepare('UPDATE sites SET st = 1 WHERE id = :id');
        $stmt->execute([':id' => $site->id]);

    }


    /**
     * Метод изменяющий состояние сайта на выкл. изменением состояния на 0
     *
     * @param $site
     */
    public static function statementSiteOff($site)
    {
        $db = General::getDb();
        $stmt = $db->prepare('UPDATE sites SET st = 0 WHERE id = :id');
        $stmt->execute([':id' => $site->id]);

    }


    /**
     * Метод заберет статистику из базы данных и заполнит в модель ->data
     * return null
     */
    public function getSiteData()
    {
        $stmt = $this->_db->prepare('SELECT site_id, `date`, prosmotr, posetit FROM sites_data WHERE site_id = :site_id');
        $stmt->execute([':site_id' => $this->id]);
        $siteData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
        foreach ($siteData as $qData) {
            $data[$qData['date']] = ['prosmotr' => $qData['prosmotr'], 'posetit' => $qData['posetit']];
        }
        $this->data = $data;
    }


    /**
     * @param $url
     * @return array
     */
    public static function getSiteByUrl($url)
    {
        $db = General::getDb();
        $site = false;
        $stmt = $db->prepare('SELECT * FROM sites WHERE url = :url');
        $stmt->execute(['url' => $url]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($data[0])) {
            $site = $data[0];
        }
        return $site;
    }

    /**
     * Получаем список сайтов, у которых состояние 1 (которые находятся в списке просмотра)
     *
     * @param bool $withData
     * @return array
     */
    public static function getSites($withData = false)
    {
        $db = General::getDb();
        $stmt = $db->prepare('SELECT url FROM sites WHERE st = 1');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sites = [];
        foreach ($data as $item) {
            $site = new Site($item['url']);
            if ($withData) {
                $site->getSiteData();
            }
            $sites[] = $site;
        }
        return $sites;
    }


}