<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ServicerequestImages;

/**
 * ServicerequestImagesSearch represents the model behind the search form of `app\models\ServicerequestImages`.
 */
class ServicerequestImagesSearch extends ServicerequestImages
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'service_request_id'], 'integer'],
            [['description', 'image', 'reftype', 'created_at', 'updated_at'], 'safe'],
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
        $query = ServicerequestImages::find();
        if($id!=''){
            $query->filterWhere(['service_request_id'=>$id]);
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
            'service_request_id' => $this->service_request_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'reftype', $this->reftype]);

        return $dataProvider;
    }
}
