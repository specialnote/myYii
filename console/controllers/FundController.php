<?php
namespace console\controllers;

use common\models\Fund;
use common\models\FundData;
use common\models\FundLog;
use common\models\Gather;
use yii\console\Controller;
use yii\web\NotFoundHttpException;
use Goutte\Client;

class FundController extends Controller{
    private $url = [
        //'http://fund.ijijin.cn/data/Net/info/zqx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',
        'http://fund.ijijin.cn/data/Net/info/hhx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',
        //'http://fund.ijijin.cn/data/Net/info/gpx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',
    ];

    //采集基金基本详情
    public function actionData(){
        $client = new Client();
        foreach($this->url as $u){
            $crawler = $client->request('GET', $u);
            $result = $crawler->text();

            $result = trim($result,'g');
            $result = trim($result,'(');
            $result = trim($result,')');
            $data_area = json_decode($result,true);
            foreach($data_area['data']['data'] as $key=>$v) {
               $this->insertData($v);
            }
        }
    }

    private static function isFund($num){
        $fund = Fund::find()->where(['num'=>trim($num)])->one();
        return $fund?true:false;
    }

    /**
     * 采集基金详情
     * @param $fundData
     * @return bool
     */
    private function insertData($fundData){
        $iopv = $fundData['net'];
        $accnav = $fundData['totalnet'];
        $growth = $fundData['ranges'];
        $rate = $fundData['rate'];
        $num = $fundData['code'];
        $date = $fundData['SYENDDATE'];
        if($num && $date && $iopv && $accnav && $growth && $rate){
            if(self::isFund($num)){
               try{
                   $fundData = new FundData();
                   $fundData->date = $date;
                   $fundData->fund_num = $num;
                   $fundData->iopv = $iopv;
                   $fundData->accnav = $accnav;
                   $fundData->growth = $growth;
                   $fundData->rate = $rate;
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
        }else{
            return false;
        }
    }


}