<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Users;

/**
 * UsersSearch represents the model behind the search form of `app\models\Users`.
 */
class UsersSearch extends Users
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'referred_by', 'status'], 'integer'],
            [['username', 'role', 'first_name', 'last_name', 'contact_no', 'email', 'company_name', 'company_address', 'company_state', 'registration_no', 'image', 'password', 'secondary_password', 'token', 'verify_token', 'reset_token', 'firebase_token', 'device_token', 'created_at', 'updated_at'], 'safe'],
            [['wallet_balance'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Users::find()->where(['!=','status',3]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'role'=> $this->role,
            'wallet_balance' => $this->wallet_balance,
            'referred_by' => $this->referred_by,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'contact_no', $this->contact_no])
            ->andFilterWhere(['like', 'email', $this->email])
          //  ->andFilterWhere(['like', 'country_of_residence', $this->country_of_residence])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'company_address', $this->company_address])
            ->andFilterWhere(['like', 'company_state', $this->company_state])
            ->andFilterWhere(['like', 'registration_no', $this->registration_no])
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
