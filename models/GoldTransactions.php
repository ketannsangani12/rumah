<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_gold_transactions".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $transaction_id
 * @property int|null $refferer_id
 * @property float|null $gold_coins
 * @property float|null $olduserbalance
 * @property float|null $newuserbalance
 * @property float|null $oldreffererbalance
 * @property float|null $newreffererbalance
 * @property string|null $reftype
 * @property string $status
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class GoldTransactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_gold_transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'transaction_id', 'refferer_id'], 'integer'],
            [['gold_coins', 'olduserbalance', 'newuserbalance', 'oldreffererbalance', 'newreffererbalance'], 'number'],
            [['reftype', 'status'], 'string'],
            [['status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'transaction_id' => 'Transaction',
            'refferer_id' => 'Refferer User',
            'gold_coins' => 'Gold Coins',
            'olduserbalance' => 'Olduserbalance',
            'newuserbalance' => 'Newuserbalance',
            'oldreffererbalance' => 'Oldreffererbalance',
            'newreffererbalance' => 'Newreffererbalance',
            'reftype' => 'Type',
            'status' => 'Status',
            'created_at' => 'Date',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
    public function getReffererser()
    {
        return $this->hasOne(Users::className(), ['id' => 'refferer_id']);
    }
}
