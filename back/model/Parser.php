<?php

class Parser
{
    public $_db;
    public $sites;

    public function __construct()
    {
        $this->_db = General::getDb();
        /**
         * получаешь данные для сайта
         * сравниваешь их с теми, что у тебя уже были
         * те, что изменились - обновляешь. новые - добавляешь
         * профит
         */
    }

    /**
     *  Загрузка данных для сайта из liveinternet
     * обработка, приведение к нужному виду
     *
     * если данные не приходят, в бд отмечается ошибка
     * и состояние ставится в ноль
     *
     * @param $site
     */
    public function loadAltSiteData($site)
    {
        $dateToday = date('Y-m-d');
        $finalData = [];
        $siteStat = [];
        $handle = file_get_contents("http://counter.yadro.ru/values?site={$site->url}"); //  http://parser/test.csv
        $data = str_replace("'", "", $handle);
        $arrData = explode(';', $data);
        array_pop ($arrData);
        foreach ($arrData as $value){
            $test = trim($value);
            $var = explode('=', $test);
            $siteStat[$var[0]] = $var[1];
        }
        foreach ($siteStat as $name => $val){
            $cut = trim(substr($name, 3));
            if ($cut == 'site'){
                $finalData[$cut] = trim(str_replace("'", "", $val));
            } else {
                $finalData[$cut] = (int)$val;
            }
        }
        if (array_key_exists ( 'error' , $finalData )) {
            Site::savePingStatementError($site);

        } else {
            $outData[$dateToday] = ['prosmotr' => $finalData['today_hit'], 'posetit' => $finalData['today_vis']];
            Site::resetPingStatementError($site);
            $this->saveSiteData($outData, $site);

        }
    }

    /**
     *
     * сравнение, обновление и сохранение данных
     *
     * @param $data
     * @param $site
     */
    private function saveSiteData($data, $site)
    {
            foreach ($data as $key => $item) {
                if (isset($site->data[$key])) {
                    if ($item['prosmotr'] > $site->data[$key]['prosmotr'] || $item['posetit'] > $site->data[$key]['posetit']) {
                        $stmt = $this->_db->prepare('UPDATE sites_data SET prosmotr = ?, posetit = ? WHERE site_id = ? AND `date` = ?');
                        $stmt->execute([$item['prosmotr'], $item['posetit'], $site->id, $key]);
                    }
                } else {
                    $stmt = $this->_db->prepare('INSERT INTO sites_data (site_id, `date`, prosmotr, posetit) VALUES (? , ? , ?, ?)');
                    $stmt->execute([$site->id, $key, $item['prosmotr'], $item['posetit']]);
                }
        }
    }
}