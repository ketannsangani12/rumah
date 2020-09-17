<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_chats".
 *
 * @property int $id
 * @property int $user_id
 * @property int $receiver_id
 * @property string $send_by
 * @property string $msg
 * @property string $msg_type
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Chats extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_chats';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'landlord_id', 'send_by','msg', 'msg_type'], 'required'],
            [['user_id', 'landlord_id'], 'integer'],
            [['msg'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['send_by', 'msg_type'], 'string', 'max' => 10],
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
            'landlord_id' => 'Receiver ID',
            'send_by' => 'Send By',
            'msg' => 'Msg',
            'msg_type' => 'Msg Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(Users::className(), ['id' => 'landlord_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
