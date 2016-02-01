<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%fund_filter}}".
 *
 * @property integer $id
 * @property string $fund_num
 * @property string $date
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
 */
class FundFilter extends \yii\db\ActiveRecord
{
    const TYPE_1 = 10;//基金总体盈利
    const TYPE_2 = 20;//基金半年盈利
    const TYPE_3 = 30;//基金成立超过半年
    const TYPE_4 = 40;//最大涨幅大于最大跌幅
    const TYPE_5 = 50;//涨幅超过5%的天数是跌幅超过5%的天数的2倍
    const TYPE_6 = 60;//增长天数是下跌天数5倍
    const TYPE_7 = 70;//80%周数在上涨

    const TYPE_8 = 80;//2015年1月到6月每月涨幅排名都在前100的基金
    const TYPE_9 = 90;//2015年9月到12月每月涨幅排名都在前100的基金


    //执行类型9:2015年9月到12月每月涨幅排名都在前100的基金
    public static function saveType9(){
        $num_1 = self::getMonthSort(2015,9,100);
        $num_2 = self::getMonthSort(2015,10,100);
        $num_3 = self::getMonthSort(2015,11,100);
        $num_4 = self::getMonthSort(2015,12,100);

        $num = array_intersect($num_1,$num_2,$num_3,$num_4);
        FundFilter::saveFilter(self::TYPE_9,$num);
    }

    //执行类型8:2015年1月到6月每月涨幅排名都在前100的基金
    public static function saveType8(){
        $num_1 = self::getMonthSort(2015,1,100);
        $num_2 = self::getMonthSort(2015,2,100);
        $num_3 = self::getMonthSort(2015,3,100);
        $num_4 = self::getMonthSort(2015,4,100);
        $num_5 = self::getMonthSort(2015,5,100);
        $num_6 = self::getMonthSort(2015,6,100);

        $num = array_intersect($num_1,$num_2,$num_3,$num_4,$num_5,$num_6);
        FundFilter::saveFilter(self::TYPE_8,$num);
    }
    /**
     * 执行类型7:80%周数在上涨
     */
    public static function saveType7(){
        $fund_nums = FundNum::find()->all();
        foreach($fund_nums as $v){
            $weekCount = FundHistory::getWeeks($v->fund_num);
            $increaseWeekCount = FundHistory::getIncreaseWeeks($v->fund_num);
            if($increaseWeekCount >=0.8*$weekCount){
                $model = new self([
                    'type'=>self::TYPE_7,
                    'date'=>date('Y-m-d'),
                    'fund_num'=>$v->fund_num
                ]);
                $model->save();
            }
        }
    }
    /**
     * 执行类型6:增长天数是下跌天数5倍
     */
    public static function saveType6(){
        $fund_nums = FundNum::find()->all();
        foreach($fund_nums as $v){
            $biggerCount = FundHistory::biggerCount($v->fund_num,0);
            $smallerCount = FundHistory::smallerCount($v->fund_num,0);
            if($biggerCount>=$smallerCount*5){
                $model = new self([
                    'type'=>self::TYPE_6,
                    'date'=>date('Y-m-d'),
                    'fund_num'=>$v->fund_num
                ]);
                $model->save();
            }
        }
    }

    /**
     * 执行类型5:涨幅超过5%的天数是跌幅超过5%的天数的2倍
     */
    public static function saveType5(){
        $fund_nums = FundNum::find()->all();
        foreach($fund_nums as $v){
            $biggerCount = FundHistory::biggerCount($v->fund_num,5);
            $smallerCount = FundHistory::smallerCount($v->fund_num,-5);
            if($biggerCount>=$smallerCount*2){
                $model = new self([
                    'type'=>self::TYPE_5,
                    'date'=>date('Y-m-d'),
                    'fund_num'=>$v->fund_num
                ]);
                $model->save();
            }
        }
    }
    /**
     * 执行类型4:最大涨幅大于最大跌幅
     */
    public static function saveType4(){
        $sql = "SELECT fund_num,(MAX(rate+0)+MIN(rate+0)) AS s FROM fund_history GROUP BY fund_num HAVING s>0";
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $posts = $command->queryAll();
        $nums = ArrayHelper::getColumn($posts,'fund_num');
        FundFilter::saveFilter(self::TYPE_4,$nums);
    }

    /**
     * 执行类型3:基金成立超过半年
     */
    public static function saveType3(){
        $sql = "SELECT DISTINCT fund_num FROM fund_history WHERE  `date`< DATE_SUB(NOW(),INTERVAL 6 MONTH)";
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $posts = $command->queryAll();
        $nums = ArrayHelper::getColumn($posts,'fund_num');
        FundFilter::saveFilter(self::TYPE_3,$nums);
    }
    /**
     * 执行类型2:基金半年盈利
     */
    public static function saveType2(){
        $sql = "SELECT fund_num,SUM(rate+0) AS r FROM fund_history WHERE `date`> DATE_SUB(NOW(),INTERVAL 6 MONTH) GROUP BY fund_num HAVING r>0";
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $posts = $command->queryAll();
        $nums = ArrayHelper::getColumn($posts,'fund_num');
        FundFilter::saveFilter(self::TYPE_2,$nums);
    }
    /**
     * 执行类型1:基金总体盈利
     */
    public static function saveType1(){
        $sql = "SELECT fund_num,SUM(rate+0) AS r FROM fund_history GROUP BY fund_num HAVING r>0";
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $posts = $command->queryAll();
        $nums = ArrayHelper::getColumn($posts,'fund_num');
        FundFilter::saveFilter(self::TYPE_1,$nums);
    }

    //将若干制定基金保存到基金过滤表
    public static function saveFilter($type,$nums){
        foreach($nums as $num){
            $model = new self([
                'type'=>$type,
                'date'=>date('Y-m-d'),
                'fund_num'=>$num
            ]);
            $model->save();
        }
    }
    //获取制定月份增长排名
    public static function getMonthSort($year,$month,$limit){
        $connection = \Yii::$app->db;
        $sql = "SELECT fund_num,SUM(rate+0) AS r FROM fund_history WHERE YEAR(`date`)= ".$year." AND MONTH(`date`)= ".$month." GROUP BY fund_num ORDER BY r DESC LIMIT ".$limit;
        $command = $connection->createCommand($sql);
        $posts = $command->queryAll();
        $num = ArrayHelper::getColumn($posts,'fund_num');
        return $num;
    }
    /**
     * 获取所有类型
     * @return array
     */
    public static function getAllTypeName(){
        return [
            self::TYPE_1=>'基金总体盈利',
            self::TYPE_2=>'基金半年盈利',
            self::TYPE_3=>'基金成立超过半年',
            self::TYPE_4=>'最大涨幅大于最大跌幅',
            self::TYPE_7=>'80%周数在上涨',
            self::TYPE_5=>'涨幅超过5%的天数是跌幅超过5%的天数的5倍',
            self::TYPE_6=>'增长天数是下跌天数3倍',
            self::TYPE_8=>'2015年1月到6月每月涨幅排名都在前100的基金',
            self::TYPE_9=>'2015年9月到12月每月涨幅排名都在前100的基金',
        ];
    }

    /**
     * 获取单个类型名称
     * @param $type
     * @return string
     */
    public static function getTypeName($type){
        switch($type){
            case self::TYPE_1:$name = '基金总体盈利';break;
            case self::TYPE_2:$name = '基金半年盈利';break;
            case self::TYPE_3:$name = '基金成立超过半年';break;
            case self::TYPE_4:$name = '最大涨幅大于最大跌幅';break;
            case self::TYPE_5:$name = '涨幅超过5%的天数是跌幅超过5%的天数的2倍';break;
            case self::TYPE_6:$name = '增长天数是下跌天数5倍';break;
            case self::TYPE_7:$name = '80%周数在上涨';break;
            case self::TYPE_8:$name = '2015年1月到6月每月涨幅排名都在前100的基金';break;
            case self::TYPE_9:$name = '2015年9月到12月每月涨幅排名都在前100的基金';break;
            default:$name = '';
        }
        return $name;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fund_filter}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['fund_num'], 'string', 'max' => 50],
            [['date'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fund_num' => 'Fund Num',
            'date' => 'Date',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}
