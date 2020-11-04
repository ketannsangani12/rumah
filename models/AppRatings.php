<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_app_ratings".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $price
 * @property string|null $service
 * @property string|null $punctuality
 * @property string|null $message
 * @property string|null $created_at
 */
class AppRatings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_app_ratings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id','price','service','punctuality'], 'required','on'=>'addrating'],
            [['user_id'], 'integer'],
            [['message'], 'string'],
            [['created_at'], 'safe'],
            [['price', 'service', 'punctuality'], 'string', 'max' => 10],
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
            'price' => 'Price',
            'service' => 'Service',
            'punctuality' => 'Punctuality',
            'message' => 'Message',
            'created_at' => 'Created At',
        ];
    }
}
