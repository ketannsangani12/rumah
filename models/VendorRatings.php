<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_vendor_ratings".
 *
 * @property int $id
 * @property int|null $request_id
 * @property int|null $property_id
 * @property int|null $user_id
 * @property int|null $vendor_id
 * @property string|null $price
 * @property string|null $service
 * @property string|null $punctuality
 * @property string|null $message
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Properties $property
 * @property Users $user
 * @property Users $vendor
 */
class VendorRatings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_vendor_ratings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_id', 'property_id', 'user_id', 'vendor_id'], 'integer'],
            [['message'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['price', 'service', 'punctuality'], 'string', 'max' => 10],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Properties::className(), 'targetAttribute' => ['property_id' => 'id']],
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
            'request_id' => 'Request',
            'property_id' => 'Property',
            'user_id' => 'User',
            'vendor_id' => 'Vendor',
            'price' => 'Price',
            'service' => 'Service',
            'punctuality' => 'Punctuality',
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

    public function getRequest()
    {
        return $this->hasOne(ServiceRequests::className(), ['id' => 'request_id']);
    }
}
