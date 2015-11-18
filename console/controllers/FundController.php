<?php
namespace console\controllers;

use common\models\Fund;
use common\models\Gather;
use yii\console\Controller;
use yii\web\NotFoundHttpException;
use Goutte\Client;

class FundController extends Controller{
    private $url = [
        'http://fund.ijijin.cn/data/Net/info/zqx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',
        //'http://fund.ijijin.cn/data/Net/info/hhx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',
        //'http://fund.ijijin.cn/data/Net/info/gpx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',
    ];

    public function actionRun(){
        $client = new Client();
        foreach($this->url as $url){
            $crawler = $client->request('GET', $url);
            $result = $crawler->text();

            $result = trim($result,'g');
            $result = trim($result,'(');
            $result = trim($result,')');
            $data_area = json_decode($result,true);
            foreach($data_area['data']['data'] as $key=>$v) {
                $num = $v['code'];
                $date = $v['SYENDDATE'];
                if($this->isExist(trim($num),trim($date))){
                    continue;
                }
                $this->insert($v);
            }
        }
    }

    private static function isExist($num,$date){
        $fund = Fund::find()->where(['num'=>trim($num),'date'=>trim($date)])->one();
        return $fund?true:false;
    }


    /**
     * 插入基金表
     * @param $fundData
     * @return bool
     */
    private static function insert($fundData){
        try{
            $fund = new Fund();
            $fund->name = $fundData['name'];
            $fund->num = trim($fundData['code']);
            $fund->company = $fundData['orgname'];
            $fund->type = $fundData['typename'];

            $fund->date = trim($fundData['SYENDDATE']);
            $fund->week = $fundData['F003N_FUND33'];
            $fund->month = $fundData['F008'];
            $fund->quarter = $fundData['F009'];
            $fund->year = $fundData['F011'];
            $fund->three_year = $fundData['F012'];
            $fund->all = $fundData['F015N_FUND33'];
            $res = $fund->save();
            return $res?true:false;
        }catch(\Exception $e){
            echo $e->getMessage().PHP_EOL;
            return false;
        }
    }

}