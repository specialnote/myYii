<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\db\Query;
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

    public $tag;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'category_id'], 'required'],
            [['content','cover_img','publish_at','author','tag'], 'string'],
            [['status', 'view_count', 'share', 'created_at', 'updated_at'], 'integer'],

            [['title'], 'string', 'max' => 100],
            [['category_id'], 'string', 'max' => 10],
            [['author'], 'string', 'max' => 30],

            ['author','filter','filter'=>function($value){
                return $value?:Yii::$app->user->identity->username;
            }],
            [['content'], 'filter','filter'=>function($value){
                return HtmlPurifier::process($value);
            }],
            ['publish_at','filter','filter'=>function($value){
                return $value?:date('Y-m-d',time());
            }],

            ['tag','match','pattern'=>Yii::$app->params['regex.tag'],'message'=>'标签不合法']
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
            'tag'=>'标签',
        ];
    }

    public function saveArticle(){
        if(!$this->validate()) return false;
        if(!$this->tag)return $this->save();

       try{
           $tags = explode(';',$this->tag);
           foreach($tags as $v){
               $tag = Tag::findOne(['name'=>$v]);
               if(!$tag){
                   $tag = new Tag();
                   $tag->name = $v;
                   $tag->article_count=0;
                   $tag->save();
               }
               $article_tag = new ArticleTag();
               $article_tag->article_id = $this->id;
               $article_tag->tag_id = $tag->id;
               $article_tag->save(false);
               //更前标签的文章数量
               $tag->article_count++;
               $tag->save(false);

           }
       }catch (Exception $e){
           throw new Exception($e->getMessage());
       }
        return $this->save();
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

    /**
     * 获取推荐文章
     * @return array|\yii\db\ActiveRecord[]
     */
   public static function getRecommendArticle(){
       $articles = self::find()
           ->where(['status'=>self::STATUS_DISPLAY])
           ->orderBy(['share'=>SORT_DESC,'publish_at'=>SORT_DESC])
           ->limit(10)
           ->all();

       return $articles;
   }

    /**
     * 获取文章标签
     * @return array
     */
    public function getArticleTag(){
        $query = new Query();
        $tags = $query->select('t.id,t.name,t.article_count')
            ->from('{{%tag}} as t')
            ->innerJoin('{{%article_tag}} as at','t.id = at.tag_id')
            ->innerJoin('{{%article}} as a','a.id = at.article_id')
            ->where(['a.id'=>$this->id])
            ->all();

        return $tags;
    }

}
