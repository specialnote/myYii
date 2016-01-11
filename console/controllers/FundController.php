<?php
namespace console\controllers;

use common\models\Fund;
use common\models\FundData;
use common\models\FundHistory;
use common\models\FundLog;
use common\models\FundNum;
use common\models\FundUrlLog;
use common\models\Gather;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use Goutte\Client;

/**
 *基金统计及分析
 */
class FundController extends Controller{


    //爱基金获取上个交易日数据
    private $url = [
        //'zqx'=>'http://fund.ijijin.cn/data/Net/info/zqx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',//债券型
        'hhx'=>'http://fund.ijijin.cn/data/Net/info/hhx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',//混合
       // 'gpx'=>'http://fund.ijijin.cn/data/Net/info/gpx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',//股票型
       // 'zsx'=>'http://fund.ijijin.cn/data/Net/info/zsx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',//指数型
       // 'bbx'=>'http://fund.ijijin.cn/data/Net/info/bbx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',//保本型
    ];

    //采集爱基金上个交易日数据
    public function actionData(){
        @set_time_limit(0);
        @ini_set('memory_limit','180M');
        $client = new Client();
        foreach($this->url as $type=>$u){
            $crawler = $client->request('GET', $u);
            $result = $crawler->text();
            $result = trim($result,'g');
            $result = trim($result,'(');
            $result = trim($result,')');
            $data_area = json_decode($result,true);
            foreach($data_area['data']['data'] as $key=>$v) {
                $this->insertData($v,$type);
            }
            $this->saveSort($type);
        }
    }

    /**
     * 每天采集基金数据
     */
    public function actionDayHistory(){
        @set_time_limit(0);
        $fundNum = FundNum::find()->all();
        $client = new Client();
        foreach($fundNum as $k=>$fund){
            $num = $fund->fund_num;
            $url =  'http://fund.eastmoney.com/f10/F10DataApi.aspx?type=lsjz&code='.$num.'&page=1&per=20';
            $crawler = $client->request('GET', $url);
            if($crawler){
                $crawler = $client->request('GET', $url);
                $crawler->filter('tr')->each(function($node) use ($num){
                    $td = $node->filter('td');
                    try{
                        if($td){
                            $date = $td->eq(0);
                            $rate = $td->eq(3);
                            if($date && $rate){
                                $date = $date->text();
                                $rate = $rate->text();
                                if(!FundHistory::find()->where(['fund_num'=>$num,'date'=>$date])->one()){
                                    echo $num.'------'.$date,'------'.time().PHP_EOL;
                                    $history = new FundHistory([
                                        'fund_num'=>$num,
                                        'date'=>$date,
                                        'rate'=>$rate
                                    ]);
                                    $history->save();
                                }
                            }
                        }
                    }catch(\Exception $e){
                        echo $e->getMessage().time().PHP_EOL;
                    }
                });
            }
        }
    }

    /**
     * 采集所有基金历史数据
     * 已采集过11
     */
    /**
      SELECT YEAR(`date`),MONTH(`date`),WEEK(`date`),SUM((rate+0)) AS sum_rate FROM fund_history WHERE fund_num = '000291' GROUP BY YEAR(`date`),MONTH(`date`),WEEK(`date`) ORDER BY YEAR(`date`) DESC,MONTH(`date`) DESC,WEEK(`date`) DESC ;
     */
    public function actionHistory($page =1){
        @set_time_limit(0);
        @ini_set('memory_limit','1280M');
        $size = 100;
        $fundNum = FundNum::find()->offset(($page-1)*$size)->limit($size)->all();
        $client = new Client();
        foreach($fundNum as $k=>$fund){
            $num = $fund->fund_num;
            $url =  'http://fund.eastmoney.com/f10/F10DataApi.aspx?type=lsjz&code='.$num.'&page=1&per=20';
            $crawler = $client->request('GET', $url);
            if($crawler){
                $content = $crawler->text();
                $content = substr($content,strpos($content,'pages:')+6);
                $pagesize = substr($content,0,strpos($content,','));
                $pagesize  = min($pagesize,10);
                for($i=1;$i<=$pagesize;$i++){
                    $url =  'http://fund.eastmoney.com/f10/F10DataApi.aspx?type=lsjz&code='.$num.'&page='.$i.'&per=20';
                    $model = FundUrlLog::find()->where(['md5_url'=>md5($url)])->one();
                    if($model)continue;
                    $crawler = $client->request('GET', $url);
                    $crawler->filter('tr')->each(function($node) use ($num){
                        $td = $node->filter('td');
                        try{
                            if($td){
                                $date = $td->eq(0);
                                $rate = $td->eq(3);
                                if($date && $rate){
                                    $date = $date->text();
                                    $rate = $rate->text();
                                    if(!FundHistory::find()->where(['fund_num'=>$num,'date'=>$date])->one()){
                                        echo $num.'------'.$date,'------'.time().PHP_EOL;
                                        $history = new FundHistory([
                                            'fund_num'=>$num,
                                            'date'=>$date,
                                            'rate'=>$rate
                                        ]);
                                        $history->save();
                                    }
                                }
                            }
                        }catch(\Exception $e){
                            echo $e->getMessage().time().PHP_EOL;
                        }
                    });

                    $url_log = new FundUrlLog([
                        'md5_url'=>md5($url),
                        'url'=>$url
                    ]);
                    $url_log->save();
                }
            }

        }
    }

    //将所有基金编码保存
    public function actionNum(){
        @set_time_limit(0);
        @ini_set('memory_limit','180M');
        $client = new Client();
        foreach($this->url as $type=>$u){
            $crawler = $client->request('GET', $u);
            $result = $crawler->text();
            $result = trim($result,'g');
            $result = trim($result,'(');
            $result = trim($result,')');
            $data_area = json_decode($result,true);
            foreach($data_area['data']['data'] as $key=>$v) {
                $model =new FundNum([
                    'fund_num'=> $v['code'],
                    'fund_type'=>$type
                ]);
                $model->save();
            }
        }
    }

    /**
     * 保存当前基金的每日排行
     * @param $type
     */
    public function saveSort($type){
        $rate_data = ArrayHelper::getColumn(FundData::find()->where(['fund_type'=>$type])->select('rate')->distinct()->orderBy(['rate'=>SORT_DESC])->asArray()->all(),'rate');
        $data = FundData::find()->where(['fund_type'=>$type])->all();
        foreach($data as $k=>$v){
            $sort = array_search($v->rate,$rate_data);
            $v->day_sort = $sort+1;
            $v->save(false);
        }
    }


    /**
     * 采集基金详情
     * @param $fundData
     * @param $type
     * @return bool
     */
    private function insertData($fundData,$type){
        $iopv = $fundData['net'];
        $accnav = $fundData['totalnet'];
        $growth = $fundData['ranges'];
        $rate = $fundData['rate'];
        $num = $fundData['code'];
        $date = $fundData['SYENDDATE'];
        if($num && $date && $iopv && $accnav && $growth && $rate){
            $month = date('n',strtotime($date));
            if($month<=3){
                $quarter = 1;
            }elseif($month<=6){
                $quarter = 2;
            }elseif($month<=9){
                $quarter = 3;
            }elseif($month<=12){
                $quarter = 4;
            }else{
                $quarter = 0;
            }
           try{
               $fundData = new FundData([
                   'date'=>$date,
                   'week'=>date('W',strtotime($date)),
                   'month'=>$month,
                   'quarter'=>$quarter,
                   'year'=>date('Y',strtotime($date)),
                   'fund_num'=>$num,
                   'fund_type'=>$type,
                   'iopv'=>$iopv,
                   'accnav'=>$accnav,
                   'growth'=>$growth,
                   'rate'=>$rate
               ]);
               $res =  $fundData->save();
               if($res){
                   //基金详情采集日志
                   FundLog::insertFundLog($num,$date,FundLog::ITEM_FUND_DATA);
                   return true;
               }else{
                   return false;
               }
           }catch(\Exception $e){
               echo $e->getMessage().PHP_EOL;
               return false;
           }

        }else{
            return false;
        }
    }


}