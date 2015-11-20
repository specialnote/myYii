<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%fund_analyze}}".
 *
 * @property integer $id
 * @property string $fund_num
 * @property string $week
 * @property string $week_average_iopv
 * @property string $week_average_growth
 * @property string $month
 * @property string $month_average
 * @property string $month_average_iopv
 * @property string $month_average_growth
 * @property integer $created_at
 * @property integer $updated_at
 */
class FundAnalyze extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fund_analyze}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fund_num'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['week', 'week_average_iopv', 'week_average_growth', 'month', 'month_average', 'month_average_iopv', 'month_average_growth','fund_num'], 'string', 'max' => 10]
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
            'week' => 'Week',
            'week_average_iopv' => 'Week Average Iopv',
            'week_average_growth' => 'Week Average Growth',
            'month' => 'Month',
            'month_average' => 'Month Average',
            'month_average_iopv' => 'Month Average Iopv',
            'month_average_growth' => 'Month Average Growth',
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
