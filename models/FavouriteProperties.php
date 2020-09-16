<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_favourite_properties".
 *
 * @property int $id
 * @property int $property_id
 * @property int $user_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Properties $property
 * @property Users $user
 */
class FavouriteProperties extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_favourite_properties';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_id', 'user_id'], 'required','on'=>'addfavourite'],
            [['property_id', 'user_id'], 'required','on'=>'removefavourite'],
          //  [['id', 'property_id', 'user_id'], 'required'],
            [['id', 'property_id', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Properties::className(), 'targetAttribute' => ['property_id' => 'id']],
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
            'property_id' => 'Property ID',
            'user_id' => 'User ID',
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
}
