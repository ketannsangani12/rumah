<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_promo_codes".
 *
 * @property int $id
 * @property string|null $promo_code
 * @property string|null $type
 * @property string|null $discount
 * @property string|null $expiry_date
 * @property string $status
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class PromoCodes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_promo_codes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promo_code','type','discount','expiry_date'], 'required','on' => 'addpromocode'],
            [['promo_code'], 'unique','on'=>'addpromocode'],
            [['type', 'status'], 'string'],
            [['expiry_date', 'created_at', 'updated_at'], 'safe'],
            [['promo_code'], 'string', 'max' => 255],
            [['discount'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'promo_code' => 'Promo Code',
            'type' => 'Type',
            'discount' => 'Discount',
            'expiry_date' => 'Expiry Date',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
