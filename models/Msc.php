<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_msc".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $request_id
 * @property string|null $type
 * @property string|null $document_no
 * @property string|null $document_front
 * @property string|null $document_back
 * @property string|null $full_name
 * @property string|null $mobile_no
 * @property string|null $mscrequest_id
 * @property string|null $activation_link
 * @property string|null $requestekyc_response
 * @property string|null $getrequeststatus_response
 * @property string|null $getactivationlink_response
 * @property string|null $signpdf_response
 * @property string|null $signedpdf
 * @property string|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property BookingRequests $request
 * @property Users $user
 */
class Msc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_msc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'request_id'], 'integer'],
            [['document_front', 'document_back', 'full_name', 'activation_link', 'requestekyc_response', 'getrequeststatus_response', 'getactivationlink_response', 'signpdf_response', 'signedpdf'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['type'], 'string', 'max' => 20],
            [['document_no', 'mobile_no', 'mscrequest_id', 'status'], 'string', 'max' => 255],
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
            'user_id' => 'User ID',
            'request_id' => 'Request ID',
            'type' => 'Type',
            'document_no' => 'Document No',
            'document_front' => 'Document Front',
            'document_back' => 'Document Back',
            'full_name' => 'Full Name',
            'mobile_no' => 'Mobile No',
            'mscrequest_id' => 'Mscrequest ID',
            'activation_link' => 'Activation Link',
            'requestekyc_response' => 'Requestekyc Response',
            'getrequeststatus_response' => 'Getrequeststatus Response',
            'getactivationlink_response' => 'Getactivationlink Response',
            'signpdf_response' => 'Signpdf Response',
            'signedpdf' => 'Signedpdf',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
