<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Users;

/**
 * UsersSearch represents the model behind the search form of `app\models\Users`.
 */
class UsersSearch extends Users
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'referred_by', 'status'], 'integer'],
            [['username', 'role', 'full_name', 'contact_no', 'email', 'company_name', 'hp_no', 'company_address', 'company_state', 'registration_no', 'bank_account_name', 'bank_account_no', 'bank_name', 'image', 'password', 'secondary_password', 'token', 'verify_token', 'reset_token', 'firebase_token', 'device_token', 'created_at', 'updated_at','document_no'], 'safe'],
            [['wallet_balance'], 'number'],
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
        $query = Users::find();

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
            'wallet_balance' => $this->wallet_balance,
            'referred_by' => $this->referred_by,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'role', $this->role])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'contact_no', $this->contact_no])
            ->andFilterWhere(['like', 'document_no', $this->document_no])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'hp_no', $this->hp_no])
            ->andFilterWhere(['like', 'company_address', $this->company_address])
            ->andFilterWhere(['like', 'company_state', $this->company_state])
            ->andFilterWhere(['like', 'registration_no', $this->registration_no])
            ->andFilterWhere(['like', 'bank_account_name', $this->bank_account_name])
            ->andFilterWhere(['like', 'bank_account_no', $this->bank_account_no])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'secondary_password', $this->secondary_password])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'verify_token', $this->verify_token])
            ->andFilterWhere(['like', 'reset_token', $this->reset_token])
            ->andFilterWhere(['like', 'firebase_token', $this->firebase_token])
            ->andFilterWhere(['like', 'device_token', $this->device_token]);

        return $dataProvider;
    }
}
