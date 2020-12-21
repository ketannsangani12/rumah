<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Properties;

/**
 * PropertiesSearch represents the model behind the search form of `app\models\Properties`.
 */
class PropertiesSearch extends Properties
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id','agent_id', 'pe_userid', 'bedroom', 'bathroom', 'carparks', 'digital_tenancy', 'auto_rental', 'insurance'], 'integer'],
            [['title', 'description', 'location', 'property_type', 'room_type', 'preference', 'availability', 'furnished_status', 'amenities', 'commute', 'status', 'created_at', 'updated_at','property_no'], 'safe'],
            [['latitude', 'longitude', 'size_of_area', 'price'], 'number'],
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
    public function search($params,$managedlisting=false)
    {
        $query = Properties::find()->where(['deleted_at'=>null]);
        if($managedlisting){
            $query->andWhere(['is_managed'=>1]);
        }else{
            $query->andWhere(['is_managed'=>0]);

        }
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
            'agent_id' => $this->agent_id,
            'property_no' => $this->property_no,
            'pe_userid' => $this->pe_userid,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'availability' => $this->availability,
            'bedroom' => $this->bedroom,
            'bathroom' => $this->bathroom,
            'carparks' => $this->carparks,
            'size_of_area' => $this->size_of_area,
            'price' => $this->price,
            'digital_tenancy' => $this->digital_tenancy,
            'auto_rental' => $this->auto_rental,
            'insurance' => $this->insurance,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'property_type', $this->property_type])
            ->andFilterWhere(['like', 'room_type', $this->room_type])
            ->andFilterWhere(['like', 'preference', $this->preference])
            ->andFilterWhere(['like', 'furnished_status', $this->furnished_status])
            ->andFilterWhere(['like', 'amenities', $this->amenities])
            ->andFilterWhere(['like', 'commute', $this->commute])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
