<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_service_requests".
 *
 * @property int $id
 * @property string|null $reference_no
 * @property int|null $property_id
 * @property int|null $vendor_id
 * @property int|null $user_id
 * @property int|null $todo_id
 * @property string|null $type
 * @property string|null $date
 * @property string|null $time
 * @property string|null $hours
 * @property string|null $description
 * @property string|null $pickup_location
 * @property string|null $dropoff_location
 * @property string|null $truck_size
 * @property string|null $document
 * @property float|null $amount
 * @property float|null $subtotal
 * @property float|null $sst
 * @property float|null $total_amount
 * @property string|null $reftype
 * @property string|null $status
 * @property string|null $booked_at
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Properties $property
 * @property TodoList $todo
 * @property Users $user
 * @property Users $vendor
 * @property ServicerequestImages[] $servicerequestImages
 * @property TodoList[] $todoLists
 */
class ServiceRequests extends \yii\db\ActiveRecord
{
    public $quote;
    public $descriptions;
    public $pictures;
    public $request_to;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_service_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'required','on' => 'changestatus'],
            [['vendor_id'], 'required','on' => 'assigndriver'],
            [['vendor_id'], 'required','on' => 'reassigndriver'],
            [['property_id', 'vendor_id', 'user_id', 'todo_id'], 'integer'],
            [['property_id' ,'request_to','vendor_id', 'date' , 'time' ], 'required','on'=>'createcleaningorder'],
            [['property_id' , 'date' , 'time' , 'type'], 'required','on'=>'bookhandyman'],
            [['user_id' , 'property_id' ,'date' , 'time' , 'truck_size' ,'pickup_location','dropoff_location'], 'required','on'=>'bookmover'],
            [['property_id' ,'date' , 'time' , 'truck_size' ,'pickup_location','dropoff_location'], 'required','on'=>'createmoverorder'],
            [['property_id' , 'date' , 'time' ,'hours'], 'required','on'=>'bookcleaner'],
            [[ 'date' , 'time','latitude','longitude','pickup_location'], 'required','on'=>'booklaundry'],

            [['quote',], 'required','on' => 'uploadquote'],
            [['quote'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
            [['date', 'booked_at', 'created_at', 'updated_at'], 'safe'],
            [['description', 'pickup_location', 'dropoff_location', 'reftype', 'status'], 'string'],
            [['amount', 'subtotal', 'sst', 'total_amount'], 'number'],
            [['reference_no', 'type', 'time', 'document'], 'string', 'max' => 255],
            [['hours'], 'string', 'max' => 10],
            [['truck_size'], 'string', 'max' => 150],
            [['latitude', 'longitude'], 'number'],

            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Properties::className(), 'targetAttribute' => ['property_id' => 'id']],
            [['todo_id'], 'exist', 'skipOnError' => true, 'targetClass' => TodoList::className(), 'targetAttribute' => ['todo_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['vendor_id' => 'id']],
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
            'property_id' => 'Property',
            'vendor_id' => 'Vendor',
            'user_id' => 'User',
            'todo_id' => 'Todo ID',
            'type' => 'Type',
            'date' => 'Date',
            'time' => 'Time',
            'hours' => 'Hours',
            'description' => 'Description',
            'descriptions'=>'Description',
            'pictures'=>'Images',
            'pickup_location' => 'Pickup Location',
            'dropoff_location' => 'Dropoff Location',
            'truck_size' => 'Truck Size',
            'document' => 'Document',
            'amount' => 'Amount',
            'subtotal' => 'Subtotal',
            'sst' => 'Sst',
            'request_to'=>'Request To',
            'total_amount' => 'Total Amount',
            'reftype' => 'Service',
            'checkin_time'=>'Check-In Time',
            'checkout_time'=>'Check-Out Time',
            'status' => 'Status',
            'quote' => 'Quotation order (PDF)',
            'booked_at' => 'Booked At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'latitude'=>'Latitude',
            'longitude'=>'Longitude'
        ];
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
     * Gets query for [[Todo]].
     *
     * @return \yii\db\ActiveQuery
     */
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

    /**
     * Gets query for [[Vendor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Users::className(), ['id' => 'vendor_id']);
    }

    /**
     * Gets query for [[ServicerequestImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServicerequestImages()
    {
        return $this->hasMany(ServicerequestImages::className(), ['service_request_id' => 'id']);
    }

    /**
     * Gets query for [[TodoLists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTodoLists()
    {
        return $this->hasMany(TodoList::className(), ['service_request_id' => 'id']);
    }
}
