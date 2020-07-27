<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TodoList;

/**
 * TodoListSearch represents the model behind the search form of `app\models\TodoList`.
 */
class TodoListSearch extends TodoList
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'request_id', 'renovation_quote_id', 'property_id', 'user_id', 'landlord_id', 'vendor_id'], 'integer'],
            [['title','description', 'document', 'reftype', 'status', 'created_at', 'updated_at','pay_from'], 'safe'],
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
    public function search($params,$type='',$daterange='')
    {

        $query = TodoList::find();
        if($type!=''){
            $query->where(['reftype'=>$type]);
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
        if($daterange!=''){
            $daterange = explode("-",$daterange);
            //print_r($daterange);exit;
            $startdate = date('Y-m-d',strtotime(str_replace('/', '-', $daterange[0])));
            $enddate = date('Y-m-d',strtotime(str_replace('/', '-', $daterange[1])));
            $query->andFilterWhere(['>=', 'rent_enddate', $startdate])

                ->andFilterWhere(['<=', 'rent_enddate', $enddate]);

        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'request_id' => $this->request_id,
            'renovation_quote_id' => $this->renovation_quote_id,
            'property_id' => $this->property_id,
            'user_id' => $this->user_id,
            'landlord_id' => $this->landlord_id,
            'vendor_id' => $this->vendor_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'reftype', $this->reftype])
            ->andFilterWhere(['like', 'pay_from', $this->pay_from])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
