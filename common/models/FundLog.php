<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%fund_log}}".
 *
 * @property integer $id
 * @property string $fund_num
 * @property string $date
 * @property string $item
 * @property integer $created_at
 * @property integer $updated_at
 */
class FundLog extends \yii\db\ActiveRecord
{
    const ITEM_FUND_NUM = 100;//表示采集基金基本信息，如编号、名称等
    const ITEM_FUND_DATA = 200;//表示采集基金数据详情
    const ITEM_FUND_WEEK_IOPV = 300;//表示分析基金每周平均净值
    const ITEM_FUND_WEEK_GROWTH= 400;//表示分析基金每周平均增长值
    const ITEM_FUND_month_IOPV = 500;//表示分析基金每月平均净值
    const ITEM_FUND_month_GROWTH= 600;//表示分析基金每月平均增长值
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fund_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fund_num','date', 'item'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['date','fund_num'], 'string', 'max' => 10],
            ['item','integer']
        ];
    }

    /**
     * 插入基金日志
     * @param $fundNum
     * @param $date
     * @param $item
     */
    public static function insertFundLog($fundNum,$date,$item){
        $fundLog = new self;
        $fundLog->fund_num = $fundNum;
        $fundLog->date = $date;
        $fundLog->item = $item;
        $fundLog->save();
    }

    /**
     * 日志是否存在
     * @param $fundNum
     * @param $date
     * @param $item
     */
    public static function isFundLog($fundNum,$date,$item){
        $fundLog = self::find()->where(['fund_num'=>trim($fundNum),'date'=>trim($date),'item'=>$item])->one();
        return $fundLog?true:false;
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
            'item' => 'Item',
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
}
