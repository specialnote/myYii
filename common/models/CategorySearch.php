<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 15-9-15
 * Time: 下午10:42
 */


namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CategorySearch extends Category
{
    public $nickname;

    public function rules()
    {
        return [
            [['name', 'slug', 'parent'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Category::find()->where(['status'=>Category::STATUS_DISPLAY]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if($this->name){
            $query->andFilterWhere(['like','name',$this->name]);
        }
        if($this->slug){
            $query->andFilterWhere(['like','slug',$this->slug]);
        }
        if($this->parent){
            $category = Category::find()->where(['like','name',$this->parent])->all();
            if($category){
                $c_ids = [];
                foreach($category as $v){
                    $c_ids[] = $v->id;
                }
                $query->andFilterWhere(['in','parent',$c_ids]);
            }else{
                $query->andFilterWhere(['in','id',[0]]);
            }

        }

        return $dataProvider;
    }
}

