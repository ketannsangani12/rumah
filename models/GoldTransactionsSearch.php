<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GoldTransactions;

/**
 * GoldTransactionsSearch represents the model behind the search form of `app\models\GoldTransactions`.
 */
class GoldTransactionsSearch extends GoldTransactions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'transaction_id', 'refferer_id'], 'integer'],
            [['gold_coins', 'olduserbalance', 'newuserbalance', 'oldreffererbalance', 'newreffererbalance'], 'number'],
            [['reftype', 'status', 'created_at', 'updated_at'], 'safe'],
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
        $query = GoldTransactions::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'transaction_id' => $this->transaction_id,
            'refferer_id' => $this->refferer_id,
            'gold_coins' => $this->gold_coins,
            'olduserbalance' => $this->olduserbalance,
            'newuserbalance' => $this->newuserbalance,
            'oldreffererbalance' => $this->oldreffererbalance,
            'newreffererbalance' => $this->newreffererbalance,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'reftype', $this->reftype])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
