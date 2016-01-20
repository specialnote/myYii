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
     * 获取每个基金每天的盈利数据
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

    /**
     * 获取每个基金每天的净值数据
     * @param $num
     * @return string
     */
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
                $sql = "SELECT `fund_num`,sum(rate+0) as rate FROM fund_history WHERE fund_num IN ".$nums." AND  ( UNIX_TIMESTAMP(`date`) BETWEEN UNIX_TIMESTAMP('".$start."') AND UNIX_TIMESTAMP('".$end."')) GROUP BY fund_num ORDER BY rate DESC LIMIT 100";
                $command = $connection->createCommand($sql);
                $posts = $command->queryAll();
                return ['sql'=>$sql,'data'=>$posts];
            }else{
                return '';
            }
        }else{
            return $this->render('sort',[
                'datas'=>[],
                'type'=>$type
            ]);
        }
    }
    /**
     * 获取筛选编号中重复的数据
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

    #######################################################################################################################################################################################################################

    /**
     * 按照周增长率排名删选基金
     * @return string
     */
    public function actionWeekFilter(){
        //拼接2016年每周信息
        $w = date('W');
        for($i=1;$i<=$w;$i++){
            $times[] = $i;
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
                    if($k==5)break;
                    $connection = \Yii::$app->db;
                    $command = $connection->createCommand("SELECT `fund_num`,WEEK(`date`) as `week`,sum(rate+0) as rate FROM fund_history WHERE fund_num IN ".$nums." AND YEAR(`date`) =2016  AND WEEK(`date`)=".$v." GROUP BY fund_num,`week` ORDER BY rate DESC LIMIT 100");
                    $posts[$k] = $command->queryAll();
                }
                return $posts;
            }
        }
        return '';
    }

    /**
     * ajax处理每周重复数据
     * @return array|string
     */
    public function actionGetWeekDuplicate(){
        $nums = FundNum::find()->select('fund_num')->distinct()->where(['fund_type'=>FundNum::TYPE_HH])->asArray()->all();
        $nums = ArrayHelper::getColumn($nums,'fund_num');
        $nums = '(\''.implode('\',\'',$nums).'\')';
        if(\Yii::$app->request->isPost){
            $w = \Yii::$app->request->post('w');
            if(count($w)>0){
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $posts = [];
                foreach($w as $k=>$v){
                    if($k==5)break;
                    $connection = \Yii::$app->db;
                    $command = $connection->createCommand("SELECT `fund_num`,WEEK(`date`) as `week`,sum(rate+0) as rate FROM fund_history WHERE fund_num IN ".$nums." AND YEAR(`date`) = 2016 AND WEEK(`date`)=".$v." GROUP BY fund_num,`week` ORDER BY rate DESC LIMIT 100");
                    $posts =array_merge($posts,ArrayHelper::getColumn($command->queryAll(),'fund_num'));
                }
                $count =  array_count_values ($posts);
                foreach($count as $key=>$v){
                    if($v !== count($w)){
                        unset($count[$key]);
                    }
                }
                return array_keys($count);
            }
        }
        return '';
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

    ######################################################################################################################################################
    ######################################################################################################################################################
    /**
     * 获取制定月份排名
     * @param int $year
     * @param int $month
     */
    public function actionMonthSort($year=2015,$month=8){
        $connection = \Yii::$app->db;
        $sql = "SELECT `fund_num`,sum(rate+0) as rate FROM fund_history WHERE   YEAR(`date`) =".$year."  AND MONTH(`date`)=".$month." GROUP BY fund_num ORDER BY rate DESC limit 20";
        $command = $connection->createCommand($sql);
        $posts = $command->queryAll();
        return $this->render('month-sort',[
            'posts'=>$posts,
            'year'=>$year,
            'month'=>$month,
            'sql'=>$sql
        ]);
    }

    ######################################################################################################################################################
    ######################################################################################################################################################
    /**
     * 2906.36
     * 计算所有基金每周增长率大于零的周数
     * 不建议使用，sql太复杂，基金成立时间不一样
     */
    public function actionWeekCount(){
        $connection = \Yii::$app->db;
        $sql = "SELECT fund_num,COUNT(*) AS `count` FROM (SELECT fund_num,WEEK(`date`) AS `week`,SUM(rate+0) AS rate FROM fund_history GROUP BY fund_num,`week` HAVING rate > 0) table_a GROUP BY fund_num";
        $command = $connection->createCommand($sql);
        $posts = $command->queryAll();
        var_dump($posts);
    }
}