<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ServiceRequests;

/**
 * ServicerequestsSearch represents the model behind the search form of `app\models\ServiceRequests`.
 */
class ServicerequestsSearch extends ServiceRequests
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'property_id', 'vendor_id', 'user_id', 'todo_id'], 'integer'],
            [['reference_no', 'type', 'date', 'time', 'hours', 'description', 'pickup_location', 'dropoff_location', 'truck_size', 'document', 'reftype', 'status', 'booked_at', 'created_at', 'updated_at'], 'safe'],
            [['amount', 'subtotal', 'sst', 'total_amount'], 'number'],
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
        $query = ServiceRequests::find();

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
            'vendor_id' => $this->vendor_id,
            'user_id' => $this->user_id,
            'todo_id' => $this->todo_id,
            'date' => $this->date,
            'amount' => $this->amount,
            'subtotal' => $this->subtotal,
            'sst' => $this->sst,
            'total_amount' => $this->total_amount,
            'booked_at' => $this->booked_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'reference_no', $this->reference_no])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'hours', $this->hours])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'pickup_location', $this->pickup_location])
            ->andFilterWhere(['like', 'dropoff_location', $this->dropoff_location])
            ->andFilterWhere(['like', 'truck_size', $this->truck_size])
            ->andFilterWhere(['like', 'document', $this->document])
            ->andFilterWhere(['like', 'reftype', $this->reftype])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
