<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Article;

/**
 * ArticleSearch represents the model behind the search form about `common\models\Article`.
 */
class ArticleGatherSearch extends Article
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'safe'],
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
        $query = Article::find()->where(['in','status',[Article::STATUS_GATHER,Article::STATUS_DISPLAY]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>  ['pageSize' => 100],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        if($this->status){
            $query->andFilterWhere(['like','status',$this->status]);
        }
        return $dataProvider;
    }
}
