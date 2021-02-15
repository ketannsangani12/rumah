<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PropertyRatings;

/**
 * PropertyratingsSearch represents the model behind the search form of `app\models\PropertyRatings`.
 */
class PropertyratingsSearch extends PropertyRatings
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'request_id', 'property_id', 'user_id'], 'integer'],
            [['comfortable', 'cleanliness', 'safety', 'appearance', 'attitude', 'knowledge', 'message', 'created_at', 'updated_at'], 'safe'],
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
    public function search($params,$id)
    {
        $query = PropertyRatings::find()->where(['property_id'=>$id]);

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
            'request_id' => $this->request_id,
            'property_id' => $this->property_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'comfortable', $this->comfortable])
            ->andFilterWhere(['like', 'cleanliness', $this->cleanliness])
            ->andFilterWhere(['like', 'safety', $this->safety])
            ->andFilterWhere(['like', 'appearance', $this->appearance])
            ->andFilterWhere(['like', 'attitude', $this->attitude])
            ->andFilterWhere(['like', 'knowledge', $this->knowledge])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
