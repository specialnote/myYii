<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Article;

/**
 * ArticleSearch represents the model behind the search form about `common\models\Article`.
 */
class ArticleSearch extends Article
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'publish_at'], 'string'],
            [['title', 'category_id', 'author'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Article::find()->where(['in','status',[Article::STATUS_HIDDEN,Article::STATUS_DISPLAY]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        if($this->publish_at){
            $query->andFilterWhere(['like','publish_at',$this->publish_at]);
        }
        if($this->title){
            $query->andFilterWhere(['like', 'title', $this->title]);
        }
        if($this->author){
            $query->andFilterWhere(['like', 'author', $this->author]);
        }
        if($this->category_id){
            $query->andFilterWhere(['category_id'=>$this->category_id]);
        }

        return $dataProvider;
    }
}
