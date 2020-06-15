<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_packages".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $price
 * @property int|null $quantity
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Packages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_packages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','price', 'quantity'], 'required'],
            [['price', 'quantity'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
