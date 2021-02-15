<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tls_categories".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string|null $image
 * @property int|null $order_position
 * @property int $is_default
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property CategoryBanners[] $categoryBanners
 * @property MerchantCategories[] $merchantCategories
 * @property UserCategories[] $userCategories
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tls_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
            [['order_position', 'is_default'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'image'], 'string', 'max' => 255],
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
            'description' => 'Description',
            'image' => 'Image',
            'order_position' => 'Order Position',
            'is_default' => 'Is Default',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CategoryBanners]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryBanners()
    {
        return $this->hasMany(CategoryBanners::className(), ['category_id' => 'id']);
    }

    /**
     * Gets query for [[MerchantCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMerchantCategories()
    {
        return $this->hasMany(MerchantCategories::className(), ['category_id' => 'id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategories::className(), ['category_id' => 'id']);
    }
}
