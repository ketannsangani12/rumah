<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_bank_accounts".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $bank_name
 * @property string|null $account_no
 * @property string|null $name_bank_account
 * @property int|null $status 1 for approved,2 for rejected,3 for pending
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Users $user
 */
class BankAccounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_bank_accounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['bank_name', 'account_no', 'name_bank_account'], 'string', 'max' => 255],
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
            'bank_name' => 'Bank Name',
            'account_no' => 'Account No',
            'name_bank_account' => 'Name Bank Account',
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
