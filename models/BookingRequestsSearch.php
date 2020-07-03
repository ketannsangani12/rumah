<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BookingRequests;

/**
 * BookingRequestsSearch represents the model behind the search form of `app\models\BookingRequests`.
 */
class BookingRequestsSearch extends BookingRequests
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'property_id', 'user_id', 'landlord_id', 'template_id', 'status'], 'integer'],
            [['credit_score', 'booking_fees', 'tenancy_fees', 'stamp_duty', 'keycard_deposit', 'sst', 'rental_deposit', 'utilities_deposit', 'security_deposit','reference_no'], 'number'],
            [['commencement_date', 'tenancy_period', 'created_at', 'updated_at'], 'safe'],
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
        $query = BookingRequests::find();

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
            'template_id' => $this->template_id,
            'credit_score' => $this->credit_score,
            'booking_fees' => $this->booking_fees,
            'tenancy_fees' => $this->tenancy_fees,
            'stamp_duty' => $this->stamp_duty,
            'keycard_deposit' => $this->keycard_deposit,
            'sst' => $this->sst,
            'reference_no'=>$this->reference_no,
            'rental_deposit' => $this->rental_deposit,
            'utilities_deposit' => $this->utilities_deposit,
            'commencement_date' => $this->commencement_date,
            'status' => $this->status,
            'security_deposit' => $this->security_deposit,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tenancy_period', $this->tenancy_period]);

        return $dataProvider;
    }
}
