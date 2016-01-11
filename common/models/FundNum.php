<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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

    public static function getRate($rate){
        $rate = number_format($rate,2);
        if($rate >5){
            return '<span style="color: #FC0000">'.$rate.'</span>';
        }elseif($rate >0){
            return '<span style="color: #FA4B4B">'.$rate.'</span>';
        }elseif($rate > -5){
            return '<span style="color: #039D50">'.$rate.'</span>';
        }else{
            return '<span style="color: #004321">'.$rate.'</span>';
        }
    }
}
