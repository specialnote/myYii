<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Fund;

/**
 * FundSearch represents the model behind the search form about `common\models\Fund`.
 */
class FundSearch extends Fund
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'num', 'date', 'week', 'month', 'quarter', 'year', 'three_year', 'all'], 'safe'],
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
        $query = Fund::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>  ['pageSize' => 100],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'num', $this->num])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'week', $this->week])
            ->andFilterWhere(['like', 'month', $this->month])
            ->andFilterWhere(['like', 'quarter', $this->quarter])
            ->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'three_year', $this->three_year])
            ->andFilterWhere(['like', 'all', $this->all]);

        return $dataProvider;
    }
}
