<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/11 0011
 * Time: 下午 4:07
 */

namespace frontend\controllers;


use common\models\FundNum;
use yii\web\NotFoundHttpException;

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
        $command = $connection->createCommand("SELECT `date`,(rate+0) as rate FROM fund_history WHERE fund_num = '".$num."' ORDER BY date DESC");
        $posts = $command->queryAll();
        return $this->render('day-detail',[
            'datas'=>$posts,
            'num'=>$num
        ]);
    }

    public function actionDayDetailData($num){
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT `date`,(rate+0) as rate FROM fund_history WHERE fund_num = '".$num."' ORDER BY date DESC");
        $posts = $command->queryAll();
        $data = [];
        foreach($posts as $v){
            $data[] = [strtotime($v['date'])*1000,floatval($v['rate'])];
        }
        return json_encode($data);
    }
}