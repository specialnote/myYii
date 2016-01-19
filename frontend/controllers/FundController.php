<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/11 0011
 * Time: 下午 4:07
 */

namespace frontend\controllers;


use common\models\FundData;
use common\models\FundNum;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class FundController extends BaseController
{
    public function actionIndex(){

    }
    /**
     * 查看每个基金每月的盈利总和
     * @param $num
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionMonthDetail($num){
        $fund = FundNum::find()->where(['fund_num'=>$num])->one();
        if(!$fund) throw new NotFoundHttpException($num.'没有找到');
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT YEAR(`date`) as `year`,MONTH(`date`) as `month`,SUM((rate+0)) AS sum_rate FROM fund_history WHERE fund_num = '".$num."' GROUP BY YEAR(`date`),MONTH(`date`) ORDER BY YEAR(`date`) DESC,MONTH(`date`) DESC;");
        $posts = $command->queryAll();
        return $this->render('month-detail',[
            'datas'=>$posts,
        ]);
    }

    /**
     * 查看每个基金每周的盈利总和
     * @param $num
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionWeekDetail($num){
        $fund = FundNum::find()->where(['fund_num'=>$num])->one();
        if(!$fund) throw new NotFoundHttpException($num.'没有找到');
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT YEAR(`date`) as `year`,MONTH(`date`) as `month`,WEEK(`date`) as `week`,SUM((rate+0)) AS sum_rate FROM fund_history WHERE fund_num = '".$num."' GROUP BY YEAR(`date`),MONTH(`date`),WEEK(`date`) ORDER BY YEAR(`date`) DESC,MONTH(`date`) DESC,WEEK(`date`) DESC ;");
        $posts = $command->queryAll();
        return $this->render('week-detail',[
            'datas'=>$posts,
        ]);
    }

    /**
     *  查看每个基金每天的盈利
     * @param $num
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDayDetail($num){
        $fund = FundNum::find()->where(['fund_num'=>$num])->one();
        if(!$fund) throw new NotFoundHttpException($num.'没有找到');
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT `date`,(rate+0) as rate FROM fund_history WHERE fund_num = '".$num."' ORDER BY `date` DESC");
        $posts = $command->queryAll();
        return $this->render('day-detail',[
            'datas'=>$posts,
            'num'=>$num
        ]);
    }

    /**
     * 获取每个基金每天的盈利总和数据
     * @param $num
     * @return string
     */
    public function actionRateDayDetailData($num){
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT `date`,(rate+0) as rate FROM fund_history WHERE fund_num = '".$num."' ORDER BY `date` ASC");
        $posts = $command->queryAll();
        $data = [];
        foreach($posts as $v){
            $data[] = [strtotime($v['date'])*1000,floatval($v['rate'])];
        }
        return json_encode($data);
    }

    public function actionPriceDayDetailData($num){
        $posts = FundData::find()->where(['fund_num'=>$num])->orderBy(['date'=>SORT_ASC])->all();
        $data = [];
        foreach($posts as $v){
            $data[] = [strtotime($v['date'])*1000,floatval($v['accnav'])];
        }
        return json_encode($data);
    }

    /**
     * 查看一段时间基金排名
     * @param string $type
     * @return array|string
     */
    public function actionSort ($type = FundNum::TYPE_ZQ){
        if(\Yii::$app->request->isPost){
            $start = Html::encode(\Yii::$app->request->post('start'));
            $end = Html::encode(\Yii::$app->request->post('end'));
            if($start && $end){
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $connection = \Yii::$app->db;
                $nums = FundNum::find()->select('fund_num')->distinct()->where(['fund_type'=>$type])->asArray()->all();
                $nums = ArrayHelper::getColumn($nums,'fund_num');
                $nums = '(\''.implode('\',\'',$nums).'\')';
                $sql = "SELECT `fund_num`,sum(rate+0) as rate FROM fund_history WHERE fund_num IN ".$nums." AND  ( UNIX_TIMESTAMP(`date`) BETWEEN UNIX_TIMESTAMP('".$start."') AND UNIX_TIMESTAMP('".$end."')) GROUP BY fund_num ORDER BY rate DESC LIMIT 50";
                $command = $connection->createCommand($sql);
                $posts = $command->queryAll();
                return ['sql'=>$sql,'data'=>$posts];
            }else{
                return '';
            }
        }else{
            return $this->render('sort',[
                'datas'=>[]
            ]);
        }
    }

    /**
     * 按照周增长率排名删选基金
     * @return string
     */
    public function actionWeekFilter(){
        $w = date('W',strtotime('2015-12-31'));
        for($i=1;$i<=$w;$i++){
            $times[] = '2015-'.$i;
        }
        $w = date('W');
        for($i=1;$i<=$w;$i++){
            $times[] = '2016-'.$i;
        }
        return $this->render('week-filter',[
            'times'=>$times,
        ]);
    }

    /**
     * ajax处理周筛选
     * @return array|string
     */
    public function actionGetWeekFilter(){
        $nums = FundNum::find()->select('fund_num')->distinct()->where(['fund_type'=>FundNum::TYPE_HH])->asArray()->all();
        $nums = ArrayHelper::getColumn($nums,'fund_num');
        $nums = '(\''.implode('\',\'',$nums).'\')';
        if(\Yii::$app->request->isPost){
            $w = \Yii::$app->request->post('w');
            if(count($w)>0){
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $posts = [];
                foreach($w as $k=>$v){
                    if($k==10)break;
                    $a = explode('-',$v);
                    $connection = \Yii::$app->db;
                    $command = $connection->createCommand("SELECT `fund_num`,WEEK(`date`) as `week`,sum(rate+0) as rate FROM fund_history WHERE fund_num IN ".$nums." AND YEAR(`date`) = ".$a[0]." AND WEEK(`date`)=".$a[1]." GROUP BY fund_num ORDER BY rate DESC LIMIT 50");
                    $posts[$k] = $command->queryAll();
                }
                return $posts;
            }
        }
        return '';
    }

    /**
     * 获取帅选编号中重复的数据
     * @return array
     */
    public function actionDuplicate(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if(\Yii::$app->request->isPost){
            $num = \Yii::$app->request->post('num','');
            $array =  explode('##',rtrim($num,'##'));
            $num = str_replace('##','',trim($num,'##'));
            $num_array = explode('_',rtrim($num,'_'));
            if($num_array){
                $count =  array_count_values ($num_array);
                foreach($count as $key=>$v){
                    if($v !== count($array)){
                        unset($count[$key]);
                    }
                }
                return ['code'=>true,'msg'=>array_keys($count)];
            }
        }
        return ['code'=>false,'msg'=>''];
    }

    /**
     * 取得上个周一
     * @return string
     */
    private function getLastMonday()
    {
        if (date('l',time()) == 'Monday') return date('Y-m-d',strtotime('last monday'));

        return date('Y-m-d',strtotime('-1 week last monday'));
    }
}