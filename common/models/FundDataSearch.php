<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FundData;

/**
 * FundDataSearch represents the model behind the search form about `common\models\FundData`.
 */
class FundDataSearch extends FundData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['date', 'fund_num', 'iopv', 'accnav', 'growth', 'rate'], 'safe'],
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
        $query = FundData::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>  ['pageSize' => 50],
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

        $query->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'fund_num', $this->fund_num])
            ->andFilterWhere(['like', 'iopv', $this->iopv])
            ->andFilterWhere(['like', 'accnav', $this->accnav])
            ->andFilterWhere(['like', 'growth', $this->growth])
            ->andFilterWhere(['like', 'rate', $this->rate]);

        return $dataProvider;
    }
}
