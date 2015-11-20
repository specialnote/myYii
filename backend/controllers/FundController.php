<?php

namespace backend\controllers;

use common\models\FundData;
use common\models\FundLog;
use Goutte\Client;
use Yii;
use common\models\Fund;
use common\models\FundSearch;
use backend\controllers\BaseController;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * FundController implements the CRUD actions for Fund model.
 */
class FundController extends BaseController
{
    private $url = [
        //'http://fund.ijijin.cn/data/Net/info/zqx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',//债券
        'http://fund.ijijin.cn/data/Net/info/hhx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',//基金
        //'http://fund.ijijin.cn/data/Net/info/gpx_F009_desc_0_0_1_9999_0_0_0_jsonp_g.html',//股票
    ];
    //采集基金基本信息
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
     * Lists all Fund models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FundSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 查看单个基金详情
     * @param $num
     * @return string
     */
    public function actionData($num){
        $dataProvider = new ActiveDataProvider([
            'query' => FundData::find()->where(['fund_num'=>$num]),
            'pagination' =>  ['pageSize' => 50],
            'sort' => [
                'defaultOrder' => [
                    'date'=>SORT_DESC,
                ]
            ],
        ]);

        return $this->render('data',[
            'dataProvider' => $dataProvider,
            'num'=>$num
        ]);
    }
    //周数据分析
    public function actionWeek(){
        set_time_limit(-1);
        $fund = Fund::find()->select('num')->distinct()->all();
        $fund = ArrayHelper::getColumn($fund,'num');
        foreach($fund as $num){
            $fundData = FundData::find()->where(['fund_num'=>$num])->groupBy('week')->all();
            foreach($fundData as $data){

            }
        }
    }
    public function  actionAssize($num){
        $month_data = [];//按月统计
        $week_data = [];//按周统计
        $data = FundData::find()->select('year')->where(['fund_num'=>$num])->distinct()->all();
        $years = ArrayHelper::getColumn($data,'year');
        $data = FundData::find()->select('month')->where(['fund_num'=>$num])->distinct()->all();
        $months = ArrayHelper::getColumn($data,'month');
        if($years){
            foreach($years as $year){
                $sql = "SELECT `month`,AVG(iopv) as `month_avg_iopv`,AVG(growth)as `month_avg_growth` ,AVG(rate)as `month_avg_rate`  FROM {{%fund_data}} WHERE `fund_num` = '".$num."' and `year`='".$year."'group by `month` order by `year` DESC , `month` DESC ";
                $month_data[$year] = Yii::$app->db->createCommand($sql)->queryAll();
                foreach($months as $month){
                    $sql = "SELECT `week`,AVG(iopv) as `week_avg_iopv`,AVG(growth)as `week_avg_growth`,AVG(rate)as `week_avg_rate`   FROM {{%fund_data}} WHERE `fund_num` = '".$num."' and `year`='".$year."' and `month`='".$month."' group by `week` order by `year` DESC , `month` DESC , `week` DESC";
                    $week_data[$year][$month] = Yii::$app->db->createCommand($sql)->queryAll();
                }
            }
        }
    //var_dump($month_data);die;
        return $this->render('assize',[
           'num'=>$num,
            'month_data'=>$month_data,
            'week_data'=>$week_data,
        ]);
    }

    /**
     * 根据fund表，将每个基金的历史数据导入数据库
     */
    public function actionImport(){
        set_time_limit(-1);
        $fund = Fund::find()->select('num')->distinct()->all();
        $fund = ArrayHelper::getColumn($fund,'num');
        foreach($fund as $num){
            if($num){
                $url = 'http://fund.10jqka.com.cn/'.$num.'/historynet.html';
                $content = file_get_contents($url);
                if(strstr($content,'JsonData = [')){
                    $content =  substr($content,strpos($content,'JsonData = [')+11);
                    $content =  substr($content,0,strpos($content,'useData = JsonData;'));
                    $content = trim($content);
                    $content = trim($content,';');
                    if($content){
                        $data = json_decode($content,true);
                        if($data){
                            foreach($data as $v){
                                if($v['date']&&$v['net']&&$v['totalnet']&&$v['inc']&&$v['rate']){
                                    $fundData = FundData::find()->where(['date'=>$v['date'],'fund_num'=>$num])->one();
                                    if($fundData)continue;
                                    $fundData = new FundData();
                                    $fundData->date = $v['date'];
                                    $fundData->week = date('W',strtotime($v['date']));
                                    $fundData->month = date('m',strtotime($v['date']));
                                    $fundData->year = date('Y',strtotime($v['date']));
                                    $fundData->fund_num = trim($num);
                                    $fundData->iopv = $v['net'];
                                    $fundData->accnav = $v['totalnet'];
                                    $fundData->growth = $v['inc'];
                                    $fundData->rate = $v['rate'];
                                    $res = $fundData->save();
                                    if($res){
                                        echo $num.'--'.$v['date'].'--'.$v['net'].'--'.$v['totalnet'].'--'.$v['inc'].'--',$v['rate'].'<br/>';
                                       FundLog::insertFundLog(trim($num),trim($v['date']),FundLog::ITEM_FUND_DATA);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

    }
    /**
     * Finds the Fund model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fund the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fund::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
