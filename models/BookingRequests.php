<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_booking_requests".
 *
 * @property int $id
 * @property int|null $property_id
 * @property int|null $user_id
 * @property int|null $landlord_id
 * @property int|null $template_id
 * @property float|null $credit_score
 * @property float|null $booking_fees
 * @property float|null $tenancy_fees
 * @property float|null $stamp_duty
 * @property float|null $fees
 * @property float|null $sst
 * @property float|null $rental_deposit
 * @property float|null $utilities_deposit
 * @property string|null $commencement_date
 * @property string|null $tenancy_period
 * @property int|null $status
 * @property float|null $security_deposit
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Users $landlord
 * @property Properties $property
 * @property AgreementTemplates $template
 * @property Users $user
 * @property Transactions[] $transactions
 */
class BookingRequests extends \yii\db\ActiveRecord
{
    public $agreement;
    public $report;
    public $movein;
    public $moveout;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_booking_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['credit_score','report'], 'required','on' => 'updatecreditscore'],
            [['agreement','movein'], 'required','on' => 'uploadagreement'],
            [['moveout_date','moveout'], 'required','on' => 'uploadmoveout'],
            [['template_id','document_content'], 'required','on' => 'choosetemplate'],
            [['agreement'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg,jpg,png,pdf'],
            [['report','movein','moveout'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
            [['property_id', 'user_id', 'landlord_id', 'template_id'], 'integer'],
            [['credit_score', 'booking_fees', 'tenancy_fees', 'stamp_duty', 'keycard_deposit', 'sst', 'rental_deposit', 'utilities_deposit', 'security_deposit'], 'number'],
            [['commencement_date', 'created_at', 'updated_at'], 'safe'],
            [['tenancy_period'], 'string', 'max' => 100],
            [['landlord_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['landlord_id' => 'id']],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Properties::className(), 'targetAttribute' => ['property_id' => 'id']],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgreementTemplates::className(), 'targetAttribute' => ['template_id' => 'id']],
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
            'property_id' => 'Property',
            'user_id' => 'Tenant',
            'reference_no'=>'Reference No.',
            'landlord_id' => 'Landlord',
            'template_id' => 'Template',
            'credit_score' => 'Credit Score',
            'booking_fees' => 'Booking Fees',
            'tenancy_fees' => 'Tenancy Fees',
            'stamp_duty' => 'Stamp Duty',
            'monthly_rental'=>'Monthly Rental',
            'keycard_deposit' => 'Keycard Deposit',
            'subtotal'=>'Sub total',
            'total' => 'Total',
            'sst' => 'Sst',
            'agreement_document'=>'Agreement document',
            'document_content'=>'Agreement content',
            'agreement'=>'Agreement',
            'rental_deposit' => 'Rental Deposit',
            'utilities_deposit' => 'Utilities Deposit',
            'commencement_date' => 'Commencement Date',
            'tenancy_period' => 'Tenancy Period',
            'status' => 'Status',
            'moveout_date'=>'Move Out Date',
            'security_deposit' => 'Security Deposit',
            'credit_score_report'=>'Credit Score Report',
            'movein_document'=>'Move In Checklist',
            'moveout_document'=>'Move Out Checklist',
            'movein'=>'Move In Checklist',
            'moveout'=>'Move Out Checklist',
            'report'=>'Credit Score Report',
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
     * Gets query for [[Template]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(AgreementTemplates::className(), ['id' => 'template_id']);
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

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transactions::className(), ['request_id' => 'id']);
    }
    public function getStatus($status)
    {
        switch ($status){
            case "New";
                return "<span class='btn btn-warning btn-xs'>New</span>";
                break;
            case "Pending";
                return "<span class='btn btn-warning btn-xs'>Pending</span>";

                break;
            case "Approved";
                return "<span class='btn bg-green btn-xs'>Approved</span>";
                break;
            case "Agreement Processed";
                return "<span class='btn bg-orange btn-xs'>Agreement Processed</span>";
                break;
            case "Declined";
                return "<span class='btn btn-danger btn-xs'>Declined</span>";
                break;
            case "Payment Requested";
                return "<span class='btn bg-blue btn-xs'>Payment Requested</span>";
                break;
            case "Rented";
                return "<span class='btn bg-green btn-xs'>Rented</span>";
                break;
            case "Terminated";
                return "<span class='btn btn-danger btn-xs'>Suspended</span>";
                break;
            case "Incompleted";
                return "<span class='btn btn-danger btn-xs'>Incompleted</span>";
                break;
        }
    }
}
