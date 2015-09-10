<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property integer $article_counts
 * @property integer $parent
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Category extends \yii\db\ActiveRecord
{
    const STATUS_DISPLAY = 10;
    const STATUS_HIDE = 20;
    const STATUS_DELETE = 30;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
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
            [['name', 'slug', 'created_at', 'updated_at'], 'required'],
            [['article_counts', 'parent', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['slug'], 'string', 'max' => 10],
            [['description'], 'string', 'max' => 500],
            [['name'], 'unique'],
            [['slug'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '分类名称',
            'slug' => '缩略名',
            'description' => '描述',
            'article_counts' => '文章数量',
            'parent' => '父级',
            'status' => '状态',
            'created_at' => '新建',
            'updated_at' => '更新',
        ];
    }

    /*
     * 获取制定父类的所有可用子类
     * @param int $parent_id 父类分类的id
     * @param int $class 层级(显示分类树的时候使用)
     * @return array
     * */
    public static function getChildCategories($parent_id,$class=1){
        $list = [];
        if($parent_id){
            //父类存在且不为0，不是第一级分类
            if(self::findOne($parent_id)){
                $category = self::find()->where(['status'=>self::STATUS_DISPLAY,'parent'=>intval($parent_id)])->asArray()->all();
            }else{
                return null;
            }
        }else{
            //父类为0或者父类不存在，默认父类为0，表示第一级分类
            $category = self::find()->where(['status'=>self::STATUS_DISPLAY,'parent'=>0])->asArray()->all();
        }
        if($category){
            foreach($category as $v){
                $v['class'] = $class;
                $list[] = $v;
                if(self::findOne($v['id'])){
                    $list = array_merge($list,self::getChildCategories($v['id'],$class+1));
                }
            }
        }
        return $list;
    }
}
