<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_topups".
 *
 * @property int $id
 * @property int|null $user_id
 * @property float|null $amount
 * @property float|null $fees
 * @property float|null $total_amount
 * @property float|null $oldbalance
 * @property float|null $newbalance
 * @property int|null $status 1 for pending,2 for completed,3 for failed
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Users $user
 */
class Topups extends \yii\db\ActiveRecord
{
    public $password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_topups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id','amount','password'], 'required','on'=>'topup'],
            [['user_id', 'status'], 'integer'],
            [['amount', 'fees', 'total_amount', 'oldbalance', 'newbalance'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
            'user_id' => 'User ID',
            'amount' => 'Amount',
            'fees' => 'Fees',
            'total_amount' => 'Total Amount',
            'oldbalance' => 'Oldbalance',
            'newbalance' => 'Newbalance',
            'password'=>'Secondary Password',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
