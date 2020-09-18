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
            [['property_id', 'sender_id', 'receiver_id','msg', 'msg_type'], 'required'],
            [['property_id','sender_id', 'receiver_id'], 'integer'],
            [['msg'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['msg_type'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'property_id' => 'User ID',
            'sender_id' => 'Sender ID',
            'receiver_id' => 'Receiver ID',
            'msg' => 'Msg',
            'msg_type' => 'Msg Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(Users::className(), ['id' => 'sender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(Users::className(), ['id' => 'receiver_id']);
    }
}
