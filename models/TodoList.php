<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_todo_list".
 *
 * @property int $id
 * @property int|null $request_id
 * @property int|null $property_id
 * @property int|null $user_id
 * @property int|null $landlord_id
 * @property int|null $vendor_id
 * @property string|null $document
 * @property string|null $reftype
 * @property string|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property TodoItems[] $todoItems
 * @property Users $landlord
 * @property Properties $property
 * @property BookingRequests $request
 * @property Users $user
 * @property Users $vendor
 */
class TodoList extends \yii\db\ActiveRecord
{
    public $datetime_range;
    public $quote;
    public $photo;
    public $tenant_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_todo_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required','on'=>'addmilestone'],
           // [['property_id'], 'required','on'=>'addinsurance'],
            [['pay_from'], 'required','on'=>'adddefectquote'],
            [['pay_from','quote'], 'required','on'=>'uploadquote'],
            [['status'], 'required','on'=>'changestatus'],
            //[['property_id','landlord_id','user_id','receive_via','commission'], 'required','on'=>'addinsurance'],
            [['description','property_id','user_id','photo'], 'required','on'=>'reportdefect'],
            [['appointment_date','property_id','tenant_id','appointment_time'], 'required','on'=>'appointment'],
            [['agent_id','user_id','landlord_id','receive_via','commission','property_id'], 'required','on'=>'transferrequest'],

            [['quote'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
            ['property_id', 'checkhavealreadyrequest','on' => 'addinsurance'],
            [['title','property_id','pay_from','due_date'], 'required','on'=>'addinvoice'],
            [['request_id', 'property_id', 'user_id', 'landlord_id', 'vendor_id'], 'integer'],
            [['reftype', 'status','remarks'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['document'], 'string', 'max' => 255],
            [['landlord_id'], 'exist', 'skipOnError' => false, 'targetClass' => Users::className(), 'targetAttribute' => ['landlord_id' => 'id']],
            [['property_id'], 'exist', 'skipOnError' => false, 'targetClass' => Properties::className(), 'targetAttribute' => ['property_id' => 'id']],
            [['request_id'], 'exist', 'skipOnError' => false, 'targetClass' => BookingRequests::className(), 'targetAttribute' => ['request_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['vendor_id' => 'id']],
            [['worker_id'], 'exist', 'skipOnError' => true, 'targetClass' => Workers::className(), 'targetAttribute' => ['worker_id' => 'id']],
            [['renovation_quote_id'], 'exist', 'skipOnError' => false, 'targetClass' => RenovationQuotes::className(), 'targetAttribute' => ['renovation_quote_id' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description'=>'Description',
            'request_id' => 'Request ID',
            'property_id' => 'Property',
            'user_id' => 'Tenant',
            'tenant_id'=>'Tenant',
            'landlord_id' => 'Landlord',
            'pay_from'=>'Payment From',
            'due_date' => 'Due Date',
            'datetime_range'=>'Date Range',
            'quote' => 'Quote',
            'appointment_date'=>'Appointment Date',
            'appointment_time'=>'Appointment Time',
            //'title' => 'Title',
            'vendor_id' => 'Vendor ID',
            'document' => 'Document',
            'reftype' => 'Reftype',
            'receive_via'=>'Receive Via',
            'commission'=>'Commission',
            'status' => 'Status',
            'photo'=>'Photo',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updated_by' =>'Updated by',
            'remarks' =>'Remarks'
        ];
    }

    /**
     * Gets query for [[TodoItems]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getTodoItems()
    {
        return $this->hasMany(TodoItems::className(), ['todo_id' => 'id']);
    }

    public function getTodoItems1()
    {
        return $this->hasMany(TodoItems::className(), ['todo_id' => 'id']);
    }
    /**
     * Gets query for [[TodoItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(TodoDocuments::className(), ['todo_id' => 'id']);
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


    public function getServicerequest()
    {
        return $this->hasOne(ServiceRequests::className(), ['id' => 'service_request_id']);
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
     * Gets query for [[Vendor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Users::className(), ['id' => 'vendor_id']);
    }

    public function getAgent()
    {
        return $this->hasOne(Users::className(), ['id' => 'agent_id']);
    }

    public function getUpdatedby()
    {
        return $this->hasOne(Users::className(), ['id' => 'updated_by']);
    }

    public function getWorker()
    {
        return $this->hasOne(Workers::className(), ['id' => 'worker_id']);
    }
    /**
     * Gets query for [[Request]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRenovationquote()
    {
        return $this->hasOne(RenovationQuotes::className(), ['id' => 'renovation_quote_id']);
    }

    public function checkhavealreadyrequest($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $request = TodoList::find()->where(['property_id'=>$this->property_id,'reftype'=>'Insurance'])->andWhere(['in', 'status', ['Unpaid','Paid']])->count();

            if ($request>0) {
                $this->addError($attribute, 'You already submitted Insurance Quote for this Property.');
            }
        }
    }
}
