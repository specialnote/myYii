<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%fund_history}}".
 *
 * @property integer $id
 * @property string $fund_num
 * @property string $date
 * @property string $rate
 * @property integer $created_at
 * @property integer $updated_at
 */
class FundHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fund_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['fund_num', 'date', 'rate'], 'string', 'max' => 50]
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
            'rate' => 'Rate',
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
     * 获取制定基金超过某个涨幅的累计天数
     * @param $num
     * @param $r
     * @return bool|null|string
     */
    public static function biggerCount($num,$r){
        $sql = "SELECT COUNT(*) AS c FROM fund_history WHERE fund_num='".$num."' AND rate>".$r;
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $posts = $command->queryScalar();
        return $posts;
    }

    /**
     * 获取制定基金低于某个涨幅的累计天数
     * @param $num
     * @param $r
     * @return bool|null|string
     */
    public static function smallerCount($num,$r){
        $sql = "SELECT COUNT(*) AS c FROM fund_history WHERE fund_num='".$num."' AND rate<".$r;
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $posts = $command->queryScalar();
        return $posts;
    }

    /**
     * 统计周数
     * @param $num
     * @return bool|null|string
     */
    public static function getWeeks($num){
        $sql = "SELECT COUNT(*) FROM (SELECT fund_num,SUM(rate+0) AS r FROM fund_history WHERE fund_num='".$num."' GROUP BY YEAR(`date`),WEEK(`date`)) t";
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $posts = $command->queryScalar();
        return $posts;
    }

    /**
     * 统计增长的周数
     * @param $num
     * @return bool|null|string
     */
    public static function getIncreaseWeeks($num){
        $sql = "SELECT COUNT(*) FROM (SELECT fund_num,SUM(rate+0) AS r FROM fund_history WHERE fund_num='".$num."' GROUP BY YEAR(`date`),WEEK(`date`) HAVING r>0) t";
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $posts = $command->queryScalar();
        return $posts;
    }
}