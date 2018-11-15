<?php

class Site
{

    public $id;
    public $url;
    public $dateCreate;
    public $dateGetData;
    public $data;

    private $_db;

    public function __construct($url = '')
    {
        $this->_db = General::getDb();

        if (!empty($url)) {
            $this->url = $url;
            $site = self::getSiteByUrl($url);
            if (empty($site)) {
                $site = $this->save();
            }
            $this->id = $site['id'];
            $this->dateCreate = $site['date_create'];
            $this->dateGetData = $site['date_get_data'];
        }

    }

    public function save()
    {
        $stmt = $this->_db->prepare('INSERT INTO sites SET url = :url');
        $stmt->execute([':url' => $this->url]);
        $site = self::getSiteByUrl($this->url);
        return $site;
    }

    public function getData()
    {

        $stmt = $this->_db->prepare('SELECT "site_id", "date" FROM sites_data ');
        $stmt->execute();
        $siteData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        var_dump($siteData);
        $this->data = [];           //передаём полученный массив


        /*
         * получи данные из таблицы с данными (о, тафтология) и заполни их в модели
         * их базы прям. не из парсера твоего нужного всем.
         * Попал в просак
         *
         * Такими темпами будешь вникать. Скоро погрузишься в портал и кончится твоя стажировка
         *
         * В смысле хорошо идешь, мне нравится)
         * Затупы твои это вообще не затупы.
         *
        */
    }

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
                $site->getData();
            }
            $sites[] = $site;
        }
        return $sites;
    }


}