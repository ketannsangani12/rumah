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
            [['user_id', 'receiver_id', 'msg', 'msg_type'], 'required'],
            [['user_id', 'receiver_id'], 'integer'],
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
            'receiver_id' => 'Receiver ID',
            'send_by' => 'Send By',
            'msg' => 'Msg',
            'msg_type' => 'Msg Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getSender()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    public function getReceiver()
    {
        return $this->hasOne(Users::className(), ['id' => 'receiver_id']);
    }
}
