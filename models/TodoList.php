<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_todo_list".
 *
 * @property int $id
 * @property int|null $request_id
 * @property int|null $property_id
 * @property int|null $user_id
 * @property int|null $landlord_id
 * @property int|null $vendor_id
 * @property string|null $document
 * @property string|null $reftype
 * @property string|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property TodoItems[] $todoItems
 * @property Users $landlord
 * @property Properties $property
 * @property BookingRequests $request
 * @property Users $user
 * @property Users $vendor
 */
class TodoList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_todo_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required','on'=>'addmilestone'],
            [['request_id', 'property_id', 'user_id', 'landlord_id', 'vendor_id'], 'integer'],
            [['reftype', 'status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['document'], 'string', 'max' => 255],
            [['landlord_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['landlord_id' => 'id']],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Properties::className(), 'targetAttribute' => ['property_id' => 'id']],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => BookingRequests::className(), 'targetAttribute' => ['request_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['vendor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'request_id' => 'Request ID',
            'property_id' => 'Property',
            'user_id' => 'User',
            'landlord_id' => 'Landlord',
            'title' => 'Title',
            'vendor_id' => 'Vendor ID',
            'document' => 'Document',
            'reftype' => 'Reftype',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[TodoItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTodoItems()
    {
        return $this->hasMany(TodoItems::className(), ['todo_id' => 'id']);
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
     * Gets query for [[Request]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(BookingRequests::className(), ['id' => 'request_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Vendor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Users::className(), ['id' => 'vendor_id']);
    }
}
