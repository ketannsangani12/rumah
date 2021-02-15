<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_users_documents".
 *
 * @property int|null $id
 * @property int|null $user_id
 * @property int|null $request_id
 * @property string|null $ekyc_document
 * @property string|null $supporting_document
 * @property string|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property BookingRequests $request
 * @property Users $user
 */
class UsersDocuments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_users_documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'request_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ekyc_document', 'supporting_document'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 50],
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
            'ekyc_document' => 'Ekyc Document',
            'supporting_document' => 'Supporting Document',
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
