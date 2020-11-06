<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transactions;

/**
 * TransactionsSearch represents the model behind the search form of `app\models\Transactions`.
 */
class TransactionsSearch extends Transactions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'property_id', 'user_id', 'landlord_id', 'vendor_id', 'promo_code', 'request_id', 'renovation_quote_id', 'topup_id', 'withdrawal_id', 'package_id', 'todo_id', 'coins'], 'integer'],
            [['reference_no', 'type', 'reftype', 'status', 'created_at', 'updated_at'], 'safe'],
            [['amount', 'sst', 'discount', 'coins_savings', 'total_amount', 'olduserbalance', 'oldlandlordbalance', 'oldagentbalance', 'oldvendorbalance', 'newuserbalance', 'newlandlordbalance', 'newagentbalance', 'newvendorcbalance'], 'number'],
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
        $query = Transactions::find();

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
            'property_id' => $this->property_id,
            'user_id' => $this->user_id,
            'landlord_id' => $this->landlord_id,
            'vendor_id' => $this->vendor_id,
            'promo_code' => $this->promo_code,
            'request_id' => $this->request_id,
            'renovation_quote_id' => $this->renovation_quote_id,
            'topup_id' => $this->topup_id,
            'withdrawal_id' => $this->withdrawal_id,
            'package_id' => $this->package_id,
            'todo_id' => $this->todo_id,
            'amount' => $this->amount,
            'sst' => $this->sst,
            'discount' => $this->discount,
            'coins' => $this->coins,
            'coins_savings' => $this->coins_savings,
            'total_amount' => $this->total_amount,
            'olduserbalance' => $this->olduserbalance,
            'oldlandlordbalance' => $this->oldlandlordbalance,
            'oldagentbalance' => $this->oldagentbalance,
            'oldvendorbalance' => $this->oldvendorbalance,
            'newuserbalance' => $this->newuserbalance,
            'newlandlordbalance' => $this->newlandlordbalance,
            'newagentbalance' => $this->newagentbalance,
            'newvendorcbalance' => $this->newvendorcbalance,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'reference_no', $this->reference_no])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'reftype', $this->reftype])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
