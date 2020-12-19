<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_devices".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $device_token
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Devices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_devices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_token', 'user_id'], 'required', 'on' => 'saveuserdevice'],
            [['user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['device_token'], 'string', 'max' => 255],
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
            'device_token' => 'Device Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
