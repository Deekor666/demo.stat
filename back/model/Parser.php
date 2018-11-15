<?php

class Parser
{
    public $_db;
    public $sites;

    public function __construct()
    {
        $this->_db = General::getDb();
        /**
         *
         * получаешь данные для сайта
         * сравниваешь их с теми, что у тебя уже были
         * те, что изменились - обновляешь. новые - добавляешь
         * профит
         *
         * P.S. заполни у модели Site массив с данными. С ними и будешь сравнивать новые
         *
         */
    }

    public function getSiteData($site)
    {
        //$site->url
        $handle = fopen("http://parser/test.csv", "rd");
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
        $days = [];
        $months = [];
        foreach ($data as $item) {
            $arrDate = explode(' ', $item['date']);
            $days[] = $arrDate[0];
            $months[] = $arrDate[1];
        }
        $finalDate = $this->_correctDate($months, $days);
        foreach ($data as $i => $item) {
            $data[$i]['date'] = $finalDate[$i];
        }
        $this->saveSiteData($data);
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

//    private function qweqwe(){
//        $test = ['01' => '12', '02'=>'12', '03'=>'12', '04'=>'12', '10' => '01', '12'=>'01', '13'=>'01','14'=>'01'];
//        $year = $this->_correctYear($test);
//        return $year;
//
//    }

    private function saveSiteData($data)
    {
        foreach ($data as $item) {
            $stmt = $this->_db->prepare('INSERT INTO sites_data (site_id, `date`, prosmotr, posetit) VALUES (? , ? , ?, ?)');
            $stmt->execute([$item['site_id'], $item['date'], $item['prosmotr'], $item['posetit']]);
        }
    }


}