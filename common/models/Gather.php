<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%gather}}".
 *
 * @property integer $id
 * @property string $url
 * @property string $url_org
 * @property string $category_id
 * @property integer $res
 * @property string $result
 * @property integer $created_at
 * @property integer $updated_at
 */
class Gather extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gather}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url','res','result'], 'required'],
            [['res', 'created_at', 'updated_at'], 'integer'],
            [['result','category','name','date'], 'string'],
            [['url'], 'string', 'max' => 32],
            [['url_org'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'url_org' => 'Url Org',
            'category_id' => 'Category ID',
            'res' => 'Res',
            'result' => 'Result',
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
