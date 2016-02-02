<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%fund_choose}}".
 *
 * @property integer $id
 * @property string $fund_num
 * @property integer $created_at
 * @property integer $updated_at
 */
class FundChoose extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fund_choose}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fund_num'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['fund_num'], 'string', 'max' => 6]
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
            'created_at' => '添加时间',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors(){
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * 计算自选基金加自选后累计涨幅
     * @return number
     */
    public function getTotalRate(){
        $history = FundHistory::find()->where(['fund_num'=>$this->fund_num])->andWhere(['>=','date',date('Y-m-d',$this->created_at)])->all();
        $rates = ArrayHelper::getColumn($history,'rate');
        return array_sum($rates);
    }
}
