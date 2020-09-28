<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_property_views".
 *
 * @property int $id
 * @property int|null $property_id
 * @property int|null $user_id
 * @property string|null $created_at
 */
class PropertyViews extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_property_views';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Properties::className(), 'targetAttribute' => ['property_id' => 'id']],

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
        ];
    }
    public function getProperty()
    {
        return $this->hasOne(Properties::className(), ['id' => 'property_id']);
    }
}
