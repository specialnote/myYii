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
    const TYPE_5 = 50;//涨幅超过5%的天数大于跌幅超过5%的天数
    const TYPE_6 = 60;//涨幅超过3%的天数大于跌幅超过3%的天数
    const TYPE_7 = 70;//增长天数大于下跌天数
    const TYPE_8 = 80;//增长周数大于下跌周数

    const TYPE_9 = 90;//80%周数在上涨
    const TYPE_11 = 110;//70%周数在上涨
    const TYPE_13 = 130;//60%周数在上涨
    const TYPE_10 = 100;//80%月数在上涨
    const TYPE_12 = 120;//70%月数在上涨
    const TYPE_14 = 140;//60%月数在上涨

    /**
     * 执行类型5:涨幅超过5%的天数大于跌幅超过5%的天数
     */
    public static function saveType5(){
        @set_time_limit(0);
        @ini_set('memory_limit','1280M');
        $model = new self();
        $model->deleteAll(['type'=>self::TYPE_5]);
        $fund_nums = FundNum::find()->all();
        $nums = [];
        foreach($fund_nums as $v){
            $biggerCount = FundHistory::biggerCount($v->fund_num,5);
            $smallerCount = FundHistory::smallerCount($v->fund_num,-5);
            if($biggerCount>$smallerCount){
                $model = new self([
                    'type'=>self::TYPE_5,
                    'date'=>date('Y-m-d'),
                    'fund_num'=>$v->fund_num
                ]);
                $model->save();
            }
        }
        FundFilter::saveFilter(self::TYPE_5,$nums);
    }
    /**
     * 执行类型4:最大涨幅大于最大跌幅
     */
    public static function saveType4(){
        @set_time_limit(0);
        @ini_set('memory_limit','1280M');
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
        @set_time_limit(0);
        @ini_set('memory_limit','1280M');
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
        @set_time_limit(0);
        @ini_set('memory_limit','1280M');
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
        @set_time_limit(0);
        @ini_set('memory_limit','1280M');
        $sql = "SELECT fund_num,SUM(rate+0) AS r FROM fund_history GROUP BY fund_num HAVING r>0";
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $posts = $command->queryAll();
        $nums = ArrayHelper::getColumn($posts,'fund_num');
        FundFilter::saveFilter(self::TYPE_1,$nums);
    }

    public static function saveFilter($type,$nums){
        $model = new self();
        $model->deleteAll(['type'=>$type]);
        foreach($nums as $num){
            $model = new self([
                'type'=>$type,
                'date'=>date('Y-m-d'),
                'fund_num'=>$num
            ]);
            $model->save();
        }
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
            self::TYPE_5=>'涨幅超过5%的天数大于跌幅超过5%的天数',
            self::TYPE_6=>'涨幅超过3%的天数大于跌幅超过3%的天数',
            self::TYPE_7=>'增长天数大于下跌天数',
            self::TYPE_8=>'增长周数大于下跌周数',
            self::TYPE_9=>'80%周数在上涨',
            self::TYPE_11=>'70%周数在上涨',
            self::TYPE_13=>'60%周数在上涨',
            self::TYPE_10=>'80%月数在上涨',
            self::TYPE_12=>'70%月数在上涨',
            self::TYPE_14=>'60%月数在上涨',
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
            case self::TYPE_5:$name = '涨幅超过5%的天数大于跌幅超过5%的天数';break;
            case self::TYPE_6:$name = '涨幅超过3%的天数大于跌幅超过3%的天数';break;
            case self::TYPE_7:$name = '增长天数大于下跌天数';break;
            case self::TYPE_8:$name = '增长周数大于下跌周数';break;
            case self::TYPE_9:$name = '80%周数在上涨';break;
            case self::TYPE_11:$name = '70%周数在上涨';break;
            case self::TYPE_13:$name = '60%周数在上涨';break;
            case self::TYPE_10:$name = '80%月数在上涨';break;
            case self::TYPE_12:$name = '70%月数在上涨';break;
            case self::TYPE_14:$name = '60%月数在上涨';break;
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
