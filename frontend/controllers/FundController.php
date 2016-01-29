<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/11 0011
 * Time: 下午 4:07
 */

namespace frontend\controllers;


use common\models\FundData;
use common\models\FundFilter;
use common\models\FundNum;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class FundController extends BaseController
{
    /**
     * 根据基金筛选条件进行筛选
     * @return string
     */
    public function actionIndex(){
        $init = [FundFilter::TYPE_1,FundFilter::TYPE_2,FundFilter::TYPE_3,FundFilter::TYPE_4,FundFilter::TYPE_7];
        $types = FundFilter::getAllTypeName();
        return $this->render('index',[
            'types'=>$types,
            'init'=>$init,
        ]);
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
        $connection = Yii::$app->db;
        $command = $connection->createCommand("SELECT YEAR(`date`) as `year`,MONTH(`date`) as `month`,SUM((rate+0)) AS sum_rate FROM fund_history WHERE fund_num = '".$num."' GROUP BY YEAR(`date`),MONTH(`date`) ORDER BY YEAR(`date`) DESC,MONTH(`date`) DESC;");
        $posts = $command->queryAll();
        $rate_data = ArrayHelper::getColumn($posts,'sum_rate');
        $sd = 0;
        if($rate_data){
            $average = array_sum($rate_data)/count($rate_data);
            $n = 0;
            foreach($rate_data as $v){
                $n += ($v - $average)*($v - $average);
            }
            $sd = sqrt($n/count($rate_data));
        }else{
            $average = 0;
        }
        return $this->render('month-detail',[
            'datas'=>$posts,
            'num'=>$num,
            'average'=>$average,
            'sd'=>$sd
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
        $connection = Yii::$app->db;
        $command = $connection->createCommand("SELECT YEAR(`date`) as `year`,MONTH(`date`) as `month`,WEEK(`date`) as `week`,SUM((rate+0)) AS sum_rate FROM fund_history WHERE fund_num = '".$num."' GROUP BY YEAR(`date`),MONTH(`date`),WEEK(`date`) ORDER BY YEAR(`date`) DESC,MONTH(`date`) DESC,WEEK(`date`) DESC ;");
        $posts = $command->queryAll();
        $rate_data = ArrayHelper::getColumn($posts,'sum_rate');
        $sd = 0;
        if($rate_data){
            $average = array_sum($rate_data)/count($rate_data);
            $n = 0;
            foreach($rate_data as $v){
                $n += ($v - $average)*($v - $average);
            }
            $sd = sqrt($n/count($rate_data));
        }else{
            $average = 0;
        }
        return $this->render('week-detail',[
            'datas'=>$posts,
            'num'=>$num,
            'average'=>$average,
            'sd'=>$sd
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
        $connection = Yii::$app->db;
        $command = $connection->createCommand("SELECT `date`,(rate+0) as rate FROM fund_history WHERE fund_num = '".$num."' ORDER BY `date` DESC");
        $posts = $command->queryAll();
        $rate_data = ArrayHelper::getColumn($posts,'rate');
        $sd = 0;
        if($rate_data){
            $average = array_sum($rate_data)/count($rate_data);
            $n = 0;
            foreach($rate_data as $v){
                $n += ($v - $average)*($v - $average);
            }
            $sd = sqrt($n/count($rate_data));
        }else{
            $average = 0;
        }
        return $this->render('day-detail',[
            'datas'=>$posts,
            'num'=>$num,
            'average'=>$average,
            'sd'=>$sd
        ]);
    }

    /**
     * 查看一段时间基金排名
     * @param string $type
     * @return array|string
     */
    public function actionSort ($type = ''){
        if(\Yii::$app->request->isPost){
            $start = Html::encode(Yii::$app->request->post('start'));
            $end = Html::encode(Yii::$app->request->post('end'));
            if($start && $end){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $connection = Yii::$app->db;
                if($type){
                    $nums = FundNum::find()->select('fund_num')->distinct()->where(['fund_type'=>$type])->asArray()->all();
                    $nums = ArrayHelper::getColumn($nums,'fund_num');
                    $nums = '(\''.implode('\',\'',$nums).'\')';
                    $sql = "SELECT `fund_num`,sum(rate+0) as rate FROM fund_history WHERE fund_num IN ".$nums." AND  ( UNIX_TIMESTAMP(`date`) BETWEEN UNIX_TIMESTAMP('".$start."') AND UNIX_TIMESTAMP('".$end."')) GROUP BY fund_num ORDER BY rate DESC LIMIT 100";
                }else{
                    $sql = "SELECT `fund_num`,sum(rate+0) as rate FROM fund_history WHERE  ( UNIX_TIMESTAMP(`date`) BETWEEN UNIX_TIMESTAMP('".$start."') AND UNIX_TIMESTAMP('".$end."')) GROUP BY fund_num ORDER BY rate DESC LIMIT 100";
                }

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
     * 获取制定月份排名
     * @param int $year
     * @param int $month
     */
    public function actionMonthSort($year=2015,$month=8){
        $connection = Yii::$app->db;
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

}