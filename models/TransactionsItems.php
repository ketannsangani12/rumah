<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_transactions_items".
 *
 * @property int $id
 * @property int|null $transaction_id
 * @property int|null $sender_id
 * @property int|null $receiver_id
 * @property float|null $percentage
 * @property float|null $amount
 * @property float|null $total_amount
 * @property float|null $oldsenderbalance
 * @property float|null $newsenderbalance
 * @property float|null $oldreceiverbalance
 * @property float|null $newreceiverbalance
 * @property string|null $type
 * @property string|null $description
 * @property string|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Users $receiver
 * @property Users $sender
 * @property Transactions $transaction
 */
class TransactionsItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_transactions_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_id', 'sender_id', 'receiver_id'], 'integer'],
            [['percentage', 'amount', 'total_amount', 'oldsenderbalance', 'newsenderbalance', 'oldreceiverbalance', 'newreceiverbalance'], 'number'],
            [['type', 'description', 'status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['receiver_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['receiver_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['sender_id' => 'id']],
            [['transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transactions::className(), 'targetAttribute' => ['transaction_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transaction_id' => 'Transaction ID',
            'sender_id' => 'Sender ID',
            'receiver_id' => 'Receiver ID',
            'percentage' => 'Percentage',
            'amount' => 'Amount',
            'total_amount' => 'Total Amount',
            'oldsenderbalance' => 'Oldsenderbalance',
            'newsenderbalance' => 'Newsenderbalance',
            'oldreceiverbalance' => 'Oldreceiverbalance',
            'newreceiverbalance' => 'Newreceiverbalance',
            'type' => 'Type',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Receiver]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(Users::className(), ['id' => 'receiver_id']);
    }

    /**
     * Gets query for [[Sender]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(Users::className(), ['id' => 'sender_id']);
    }

    /**
     * Gets query for [[Transaction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(Transactions::className(), ['id' => 'transaction_id']);
    }
}
