<?php

namespace common\models;

use Yii;
use yii\helpers\HtmlPurifier;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $category_id
 * @property string $author
 * @property integer $status
 * @property integer $view_count
 * @property integer $share
 * @property integer $publish_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class Article extends \yii\db\ActiveRecord
{
    const STATUS_DISPLAY = 10;
    const STATUS_HIDDEN = 20;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'category_id','author', 'publish_at'], 'required'],
            [['content','cover_img'], 'string'],
            [['status', 'view_count', 'share', 'publish_at', 'created_at', 'updated_at'], 'integer'],

            [['title'], 'string', 'max' => 100],
            [['category_id'], 'string', 'max' => 10],
            [['author'], 'string', 'max' => 30],

            [['content'], 'filter','filter'=>function($value){
                return HtmlPurifier::process($value);
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'category_id' => '分类',
            'author' => '作者',
            'status' => '状态',
            'cover_img' => '封面图片',
            'view_count' => '浏览量',
            'share' => '分享量',
            'publish_at' => '发布时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
