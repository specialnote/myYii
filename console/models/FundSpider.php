<?php
    namespace console\models;

    use common\models\Fund;
    use common\models\Gather;

    class FundSpider{
        protected $category = '';//分类
        protected $baseUrl = '';//域名
        protected $name = '';//网站名称

        /**
         * 判断文章是否采集
         * @param $url
         * @param $date
         * @return bool
         */
        protected function isGathered($url,$date){
            $gather = Gather::find()->where(['url'=>md5(trim($url)),'res'=>true,'date'=>trim($date)])->one();
            return $gather?true:false;
        }

        /**
         * 插入基金表
         * @param $name
         * @param $num
         * @param $date
         * @param $week
         * @param $month
         * @param $quarter
         * @param $year
         * @param $three_year
         * @param $all
         */
       public static function insert($name,$num,$date,$week,$month,$quarter,$year,$three_year,$all){
          try{
              $fund = new Fund();
              $fund->name = $name;
              $fund->num = $num;
              $fund->date = $date;
              $fund->week = $week;
              $fund->month = $month;
              $fund->quarter = $quarter;
              $fund->year = $year;
              $fund->three_year = $three_year;
              $fund->all = $all;
              $res = $fund->save(false);
              return $res?true:false;
          }catch(\Exception $e){
              echo $e->getMessage().PHP_EOL;
          }
       }

        /**
         * 采集日志
         * @param $url
         * @param $category
         * @param $res
         * @param $result
         */
        public function addLog($url,$category,$res,$result){
            $gather = new Gather();
            $gather->name = $this->name;
            $gather->category = $category;
            $gather->url = md5($url);
            $gather->url_org = $url;
            $gather->res = $res;
            $gather->result = $result;
            $gather->save();
        }
    }
?>