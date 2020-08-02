<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_property_ratings".
 *
 * @property int $id
 * @property int|null $request_id
 * @property int|null $property_id
 * @property int|null $user_id
 * @property string|null $comfortable
 * @property string|null $cleanliness
 * @property string|null $safety
 * @property string|null $appearance
 * @property string|null $attitude
 * @property string|null $knowledge
 * @property string|null $message
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Properties $property
 * @property BookingRequests $request
 * @property Users $user
 */
class PropertyRatings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_property_ratings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_id', 'property_id', 'user_id'], 'integer'],
            [['message'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['comfortable', 'cleanliness', 'safety', 'appearance', 'attitude', 'knowledge'], 'string', 'max' => 10],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Properties::className(), 'targetAttribute' => ['property_id' => 'id']],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => BookingRequests::className(), 'targetAttribute' => ['request_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'request_id' => 'Request',
            'property_id' => 'Property',
            'user_id' => 'User',
            'comfortable' => 'Comfortable',
            'cleanliness' => 'Cleanliness',
            'safety' => 'Safety',
            'appearance' => 'Appearance',
            'attitude' => 'Attitude',
            'knowledge' => 'Knowledge',
            'message' => 'Message',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
}
