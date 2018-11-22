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
     * @param $site
     */
    public function loadSiteData($site)
    {

        $handle = fopen("https://www.liveinternet.ru/stat/{$site->url}/index.csv?graph=csv", "rd"); //http://parser/test.csv
        $data = [];
        $i = -1;
        while (!feof($handle)) {
            $line = fgetcsv($handle, 1024);
            if (!empty($line[0]) && $i >= 0) {
                $strArr = explode(";", $line[0]);
                $data[$i]['site_id'] = $site->id;
                $data[$i]['date'] = $strArr[0];
                $data[$i]['prosmotr'] = $strArr[1];
                $data[$i]['posetit'] = $strArr[2];
            }
            $i++;
        }
        fclose($handle);
        if (!empty($data)) {
            Site::resetPingStatementError($site);

            $days = [];
            $months = [];
            foreach ($data as $item) {
                $arrDate = explode(' ', $item['date']);
                $days[] = $arrDate[0];
                $months[] = $arrDate[1];
            }
            $finalDate = $this->_correctDate($months, $days);
            $resultData = [];
            foreach ($data as $i => $item) {
                $data[$i]['date'] = $finalDate[$i];
                $resultData[$finalDate[$i]] = ['prosmotr' => $data[$i]['prosmotr'], 'posetit' => $data[$i]['posetit']];
            }
            $this->saveSiteData($resultData, $site);
        } else {
            Site::savePingStatementError($site);


        }
    }

    private function _correctDate($months, $days)
    {
        $years = [];
        $strMonth = ['01' => 'янв', '02' => 'фев', '03' => 'мар', '04' => 'апр', '05' => 'май', '06' => 'июн', '07' => 'июл', '08' => 'авг', '09' => 'сен', '10' => 'окт', '11' => 'ноя', '12' => 'дек'];
        $correctedMonths = [];
        foreach ($months as $month) {
            $monthNum = array_search($month, $strMonth);
            $correctedMonths[] = $monthNum;
        }

        foreach ($correctedMonths as $month) {
            if ($month == 12 && in_array('12', $correctedMonths) && in_array('01', $correctedMonths)) {
                $years[] = (date('Y') - 1);
            } else {
                $years[] = (date('Y'));
            }
        }

        $i = 0;
        $finalDate = [];
        foreach ($days as $day) {
            $finalDate[$i] = "$years[$i]-$correctedMonths[$i]-$day";
            $i++;
        }
        return $finalDate;
    }

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