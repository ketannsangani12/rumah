<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_manual_kyc".
 *
 * @property int $id
 * @property int|null $request_id
 * @property int|null $user_id
 * @property string|null $type
 * @property string|null $document
 * @property string|null $selfie
 * @property string $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property BookingRequests $request
 * @property Users $user
 */
class ManualKyc extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_manual_kyc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status','file'], 'required','on'=>'updatestatus'],
            [['request_id', 'user_id'], 'integer'],
            [['document', 'selfie', 'status','document_back','reason','full_name'], 'string'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
            [['created_at', 'updated_at'], 'safe'],
            [['type'], 'string', 'max' => 5],
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
            'request_id' => 'Booking Request',
            'user_id' => 'User',
            'type' => 'Type',
            'full_name'=>'Full Name',
            'document' => 'Document',
            'selfie' => 'Selfie',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'document_no'=>'Document No.',
            'reason'=>'Reason',
            'file'=>'PDF'
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
