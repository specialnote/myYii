<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/11 0011
 * Time: 下午 4:07
 */

namespace frontend\controllers;


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
     *  查看每个基金每天的盈利总和
     * @param $num
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDayDetail($num){
        $fund = FundNum::find()->where(['fund_num'=>$num])->one();
        if(!$fund) throw new NotFoundHttpException($num.'没有找到');
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT `date`,(rate+0) as rate FROM fund_history WHERE fund_num = '".$num."' ORDER BY `date` ASC");
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
    public function actionDayDetailData($num){
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT `date`,(rate+0) as rate FROM fund_history WHERE fund_num = '".$num."' ORDER BY `date` ASC");
        $posts = $command->queryAll();
        $data = [];
        foreach($posts as $v){
            $data[] = [strtotime($v['date'])*1000,floatval($v['rate'])];
        }
        return json_encode($data);
    }

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
                $command = $connection->createCommand("SELECT `fund_num`,sum(rate+0) as rate FROM fund_history WHERE fund_num IN ".$nums." AND  ( UNIX_TIMESTAMP(`date`) BETWEEN UNIX_TIMESTAMP('".$start."') AND UNIX_TIMESTAMP('".$end."')) GROUP BY fund_num ORDER BY rate DESC LIMIT 50");
                $posts = $command->queryAll();
                return $posts;
            }else{
                return '';
            }
        }else{
            return $this->render('sort',[
                'datas'=>[]
            ]);
        }

    }
}