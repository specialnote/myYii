<?php
namespace console\controllers;

use common\models\Fund;
use common\models\FundData;
use common\models\FundLog;
use common\models\Gather;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use Goutte\Client;

class FundController extends Controller{
    private $url = [
        'zqx'=>'http://fund.ijijin.cn/data/Net/info/zqx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',//债券型
        'hhx'=>'http://fund.ijijin.cn/data/Net/info/hhx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',//混合
        'gpx'=>'http://fund.ijijin.cn/data/Net/info/gpx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',//股票型
        'zsx'=>'http://fund.ijijin.cn/data/Net/info/zsx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',//指数型
        'bbx'=>'http://fund.ijijin.cn/data/Net/info/bbx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',//保本型
    ];

    //采集爱基金上个交易日数据
    public function actionData(){
        @set_time_limit(0);
        $client = new Client();
        foreach($this->url as $type=>$u){
            $crawler = $client->request('GET', $u);
            $result = $crawler->text();

            $result = trim($result,'g');
            $result = trim($result,'(');
            $result = trim($result,')');
            $data_area = json_decode($result,true);
            $k = 0;
            foreach($data_area['data']['data'] as $key=>$v) {
                if($k>5)break;
                $this->insertData($v,$type);
                $k ++;
            }
            $this->saveSort($type);
        }

    }

    public function saveSort($type){
        $rate_data = ArrayHelper::getColumn(FundData::find()->where(['fund_type'=>$type])->select('rate')->distinct()->orderBy(['rate'=>SORT_DESC])->asArray()->all(),'rate');
        $data = FundData::find()->where(['fund_type'=>$type])->all();
        foreach($data as $k=>$v){
            $sort = array_search($v->rate,$rate_data);
            $v->day_sort = $sort+1;
            $v->save(false);
        }
    }

    private static function isFund($num){
        $fund = Fund::find()->where(['num'=>trim($num)])->one();
        return $fund?true:false;
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