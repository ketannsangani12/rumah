<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_renovation_quotes".
 *
 * @property int $id
 * @property int|null $property_id
 * @property int|null $landlord_id
 * @property string|null $quote_document
 * @property string|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Users $landlord
 * @property Properties $property
 * @property TodoList[] $todoLists
 */
class RenovationQuotes extends \yii\db\ActiveRecord
{
    public $document;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_renovation_quotes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_id','document'], 'required','on' => 'addquote'],
            ['property_id', 'checkhavealreadyrequest','on' => 'addquote'],
            [['property_id', 'landlord_id'], 'integer'],
            [['document'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
            [['status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['quote_document'], 'string', 'max' => 255],
            [['landlord_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['landlord_id' => 'id']],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Properties::className(), 'targetAttribute' => ['property_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'property_id' => 'Property',
            'landlord_id' => 'Landlord',
            'quote_document' => 'Quote Document',
            'document'=>'Renovation Quote Document',
            'status' => 'Status',
            'created_at' => 'Date',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Landlord]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLandlord()
    {
        return $this->hasOne(Users::className(), ['id' => 'landlord_id']);
    }

    /**
     * Gets query for [[Property]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Properties::className(), ['id' => 'property_id']);
    }

    /**
     * Gets query for [[TodoLists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTodoLists()
    {
        return $this->hasMany(TodoList::className(), ['renovation_quote_id' => 'id']);
    }
    public function checkhavealreadyrequest($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $request = RenovationQuotes::find()->where(['in', 'status', ['Pending','Approved','Work In Progress']])->count();

            if ($request>0) {
                $this->addError($attribute, 'You already submitted Renovation Quote for this Property.');
            }
        }
    }
}
