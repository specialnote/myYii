<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "fund_data".
 *
 * @property integer $id
 * @property string $fund_id
 * @property string $iopv
 * @property string $accnav
 * @property string $growth
 * @property string $rate
 * @property integer $created_at
 * @property integer $updated_at
 */
class FundData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fund_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fund_num', 'date'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['fund_num'], 'string', 'max' => 50],
            [['iopv', 'accnav', 'rate','month','week'], 'string', 'max' => 10],
            [['growth'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fund_num' => '编号',
            'date' => '日期',
            'iopv' => '单位净值',
            'accnav' => '累计净值',
            'growth' => '增长值',
            'rate' => '增长率(%)',
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
