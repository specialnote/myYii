<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%article_tag}}".
 *
 * @property integer $id
 * @property integer $article_id
 * @property integer $tag_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class ArticleTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'tag_id'], 'required'],
            [['article_id', 'tag_id', 'created_at', 'updated_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Article ID',
            'tag_id' => 'Tag ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 可以自动补全 created_at 和 updated_at
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * 将指定文章的所有标签文章数加1
     * @param $article_id
     * @return bool
     */
    public static function updateTagArticleCounts($article_id){
        $articleTag = ArticleTag::find()->where(['article_id'=>$article_id])->all();
        if(!$articleTag)return false;
        foreach($articleTag as $v){
            Tag::updateAllCounters(['article_count'=>1],['id'=>$v->tag_id]);
        }
        return true;
    }
}
