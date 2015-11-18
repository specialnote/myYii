<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%fund}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $num
 * @property string $date
 * @property string $week
 * @property string $month
 * @property string $quarter
 * @property string $year
 * @property string $three_year
 * @property string $all
 * @property integer $created_at
 * @property integer $updated_at
 */
class Fund extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fund}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name','date'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'num','company','type'], 'string', 'max' => 50],
            [['date', 'week', 'month', 'quarter', 'year', 'three_year', 'all'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'num' => '编号',
            'date' => '日期',
            'week' => '周收益',
            'month' => '月收益',
            'quarter' => '季收益',
            'year' => '年收益',
            'three_year' => '三年收益',
            'all' => '总收益',
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
