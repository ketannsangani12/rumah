<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_payments".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $order_id
 * @property int|null $todo_id
 * @property int|null $package_id
 * @property float|null $amount
 * @property float|null $sst
 * @property float|null $total_amount
 * @property int|null $promo_code
 * @property float|null $discount
 * @property int|null $coins
 * @property float|null $coins_savings
 * @property string|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Payments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'todo_id', 'package_id', 'promo_code', 'coins'], 'integer'],
            [['amount', 'sst', 'total_amount', 'discount', 'coins_savings'], 'number'],
            [['status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['order_id'], 'string', 'max' => 255],
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
            'order_id' => 'Order ID',
            'todo_id' => 'Todo ID',
            'package_id' => 'Package ID',
            'amount' => 'Amount',
            'sst' => 'Sst',
            'total_amount' => 'Total Amount',
            'promo_code' => 'Promo Code',
            'discount' => 'Discount',
            'coins' => 'Coins',
            'coins_savings' => 'Coins Savings',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    /**
     * Gets query for [[Landlord]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
    public function getTodo()
    {
        return $this->hasOne(TodoList::className(), ['id' => 'todo_id']);
    }
}
