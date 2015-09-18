<?php

namespace common\models;

use Yii;
use yii\helpers\FileHelper;
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
            [['title', 'content', 'category_id', 'publish_at'], 'required'],
            [['content','cover_img','publish_at','author'], 'string'],
            [['status', 'view_count', 'share', 'created_at', 'updated_at'], 'integer'],

            [['title'], 'string', 'max' => 100],
            [['category_id'], 'string', 'max' => 10],
            [['author'], 'string', 'max' => 30],
          //  ['cover_img','validateImg'],
            ['author','filter','filter'=>function($value){
                return $value?$value:Yii::$app->user->identity->username;
            }],

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

    /*
     * 获取文章状态名称
     * */
    public function getStatusName($status){
        if($status == Article::STATUS_DISPLAY){
            return '显示';
        }elseif($status == Article::STATUS_HIDDEN){
            return '隐藏';
        }else{
            return '';
        }
    }
   /* public function validateImg($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $file = \Yii::$app->basePath.'/web/'.ltrim($this->cover_img,'/');

            if(is_file($file)){
                $extension = pathinfo($file,PATHINFO_EXTENSION);
                if(!in_array($extension,['jpg','png','jpeg','bmp','gif'])){
                    $this->addError($attribute,'文件后缀不合法');
                }
            }else{
                $this->addError($attribute,'文件不存在');
            }
        }
    }*/
    /*public function beforeSave($insert){
        if(parent::beforeSave($insert)){
            $this->publish_at = strtotime($this->publish_at);
        }
    }*/
}
