<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_transactions".
 *
 * @property int $id
 * @property int|null $reference_no
 * @property int|null $property_id
 * @property int|null $user_id
 * @property int|null $landlord_id
 * @property int|null $promo_code
 * @property int|null $request_id
 * @property float|null $amount
 * @property float|null $discount
 * @property int|null $coins
 * @property float|null $total_amount
 * @property float|null $olduserbalance
 * @property float|null $oldlandlordbalance
 * @property float|null $oldvendorbalance
 * @property float|null $newuserbalance
 * @property float|null $newlandlordbalance
 * @property float|null $newvendorcbalance
 * @property string|null $reftype
 * @property string $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Users $landlord
 * @property Properties $property
 * @property BookingRequests $request
 * @property Users $user
 */
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reference_no', 'property_id', 'user_id', 'landlord_id', 'promo_code', 'request_id', 'coins'], 'integer'],
            [['amount', 'discount', 'total_amount', 'olduserbalance', 'oldlandlordbalance', 'oldvendorbalance', 'newuserbalance', 'newlandlordbalance', 'newvendorcbalance'], 'number'],
            [['reftype', 'status'], 'string'],
            [['status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['landlord_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['landlord_id' => 'id']],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Properties::className(), 'targetAttribute' => ['property_id' => 'id']],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => BookingRequests::className(), 'targetAttribute' => ['request_id' => 'id']],
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
            'reference_no' => 'Reference No',
            'property_id' => 'Property ID',
            'user_id' => 'User ID',
            'landlord_id' => 'Landlord ID',
            'promo_code' => 'Promo Code',
            'request_id' => 'Request ID',
            'amount' => 'Amount',
            'discount' => 'Discount',
            'coins' => 'Coins',
            'total_amount' => 'Total Amount',
            'olduserbalance' => 'Olduserbalance',
            'oldlandlordbalance' => 'Oldlandlordbalance',
            'oldvendorbalance' => 'Oldvendorbalance',
            'newuserbalance' => 'Newuserbalance',
            'newlandlordbalance' => 'Newlandlordbalance',
            'newvendorcbalance' => 'Newvendorcbalance',
            'reftype' => 'Reftype',
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
    public function getLandlord()
    {
        return $this->hasOne(Users::className(), ['id' => 'landlord_id']);
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
     * Gets query for [[Request]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(BookingRequests::className(), ['id' => 'request_id']);
    }


    public function getTodo()
    {
        return $this->hasOne(TodoList::className(), ['id' => 'todo_id']);
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
    public function getTransactionitems()
    {
        return $this->hasMany(TransactionsItems::className(), ['transaction_id' => 'id']);
    }
}
