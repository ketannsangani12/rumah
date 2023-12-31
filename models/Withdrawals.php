<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_withdrawals".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $bank_id
 * @property string|null $reference_no
 * @property float|null $amount
 * @property float|null $fees
 * @property float|null $total_amount
 * @property float|null $old_balance
 * @property float|null $new_balance
 * @property string|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Users $user
 */
class Withdrawals extends \yii\db\ActiveRecord
{
    public $password;
    public $proof;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_withdrawals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id','amount','password'], 'required','on'=>'userwithdrawal'],
            [['status','proof'], 'required','on'=>'updatewithdrawal'],
            [['user_id', 'bank_id'], 'integer'],
            [['amount', 'fees', 'total_amount', 'old_balance', 'new_balance'], 'number'],
            [['status','remarks'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['reference_no'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['updated_by' => 'id']],

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
            'bank_id' => 'Bank',
            'reference_no' => 'Reference No',
            'amount' => 'Amount',
            'fees' => 'Fees',
            'remarks'=>'Remarks',
            'proof' =>'File',
            'total_amount' => 'Total Amount',
            'old_balance' => 'Old Balance',
            'new_balance' => 'New Balance',
            'password'=>'Secondary Password',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Last Updated',
            'updated_by' => 'Updated By'
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
    public function getUpdatedby()
    {
        return $this->hasOne(Users::className(), ['id' => 'updated_by']);
    }
}
