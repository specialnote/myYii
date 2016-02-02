<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%fund_num}}".
 *
 * @property integer $id
 * @property string $fund_num
 * @property string $fund_type
 * @property integer $created_at
 * @property integer $updated_at
 */
class FundNum extends \yii\db\ActiveRecord
{
    const TYPE_ZQ = 'zqx';//债券
    const TYPE_GG = 'gpx';//股票
    const TYPE_ZS = 'zsx';//指数
    const TYPE_HH = 'hhx';//混合
    const TYPE_BB = 'bbx';//保本

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fund_num}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['fund_num', 'fund_type'], 'string', 'max' => 50]
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
            'fund_type' => 'Fund Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * 处理基金增长率
     * @param $rate
     * @return string
     */
    public static function getRate($rate){
        $rate = number_format($rate,2);
        if($rate >5){
            return '<span style="color: #FC0000;font-size: 16px;font-weight: 700;">'.$rate.'</span>';
        }elseif($rate >0){
            return '<span style="color: #FA4B4B">'.$rate.'</span>';
        }elseif($rate > -5){
            return '<span style="color: #039D50">'.$rate.'</span>';
        }else{
            return '<span style="color: green;font-size: 16px;font-weight: 700;">'.$rate.'</span>';
        }
    }

    /**
     * 获取制定基金类型名称
     * @param $num
     * @return string
     */
    public static function getTypeName($num){
        $fund = FundNum::find()->where(['fund_num'=>$num])->one();
        if(!$fund)return '';
        $type = $fund->fund_type;
        switch($type){
            case self::TYPE_ZQ:$name = '债券';break;
            case self::TYPE_GG:$name = '股票';break;
            case self::TYPE_ZS:$name = '指数';break;
            case self::TYPE_HH:$name = '混合';break;
            case self::TYPE_BB:$name = '保本';break;
            default:$name = '';
        }
        return $name;
    }

    /**
     * 获取每日分析
     * @param $num
     * @return array
     */
    public static function getFundDetail($num,$type){
        if(!in_array($type,['day','week','month']))return '';
        $connection = Yii::$app->db;
        if($type == 'day'){
            $sql = "SELECT `date`,(rate+0) as sum_rate FROM fund_history WHERE fund_num = '".$num."' ORDER BY `date` DESC";
        }elseif($type == 'week'){
            $sql = "SELECT YEAR(`date`) as `year`,MONTH(`date`) as `month`,WEEK(`date`) as `week`,SUM((rate+0)) AS sum_rate FROM fund_history WHERE fund_num = '".$num."' GROUP BY YEAR(`date`),MONTH(`date`),WEEK(`date`) ORDER BY YEAR(`date`) DESC,MONTH(`date`) DESC,WEEK(`date`) DESC ";
        }elseif($type == 'month'){
            $sql = "SELECT YEAR(`date`) as `year`,MONTH(`date`) as `month`,SUM((rate+0)) AS sum_rate FROM fund_history WHERE fund_num = '".$num."' GROUP BY YEAR(`date`),MONTH(`date`) ORDER BY YEAR(`date`) DESC,MONTH(`date`) DESC";
        }else{
            return '';
        }
        $command = $connection->createCommand($sql);
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
        return [
            'average'=>$average,//均值
            'sd'=>$sd,//标准差,
            'sum'=>array_sum($rate_data)
        ];
    }

}
