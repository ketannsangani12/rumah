<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_properties".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $pe_userid
 * @property string|null $title
 * @property string|null $description
 * @property string|null $location
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $property_type
 * @property string|null $room_type
 * @property string|null $preference
 * @property string|null $availability
 * @property int|null $bedroom
 * @property int|null $bathroom
 * @property int|null $carparks
 * @property string|null $furnished_status
 * @property float|null $size_of_area
 * @property float|null $price
 * @property string|null $amenities
 * @property string|null $commute
 * @property int|null $digital_tenancy
 * @property int|null $auto_rental
 * @property int|null $insurance
 * @property string|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Images[] $images
 * @property Users $peUser
 * @property Users $user
 */
class Properties extends \yii\db\ActiveRecord
{
    public $pictures;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_properties';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'location','latitude','longitude','property_type', 'room_type', 'preference' ,'bedroom', 'bathroom', 'carparks','type','furnished_status','availability','size_of_area','price','amenities','commute','status'], 'required','on'=>'create'],
            [['title', 'description', 'location','latitude','longitude','property_type', 'room_type', 'preference' ,'bedroom', 'bathroom', 'carparks','type','furnished_status','availability','size_of_area','price','amenities','pictures'], 'required','on'=>'addproperty'],
           // [['pictures'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 10, 'extensions' => 'png, jpg,jpeg','on'=>'addproperty'],
            [['user_id', 'pe_userid', 'bedroom', 'bathroom', 'carparks', 'digital_tenancy', 'auto_rental', 'insurance','is_featured'], 'integer'],
            [['title', 'description', 'location', 'status','remarks','commute'], 'string'],
            [['latitude', 'longitude', 'size_of_area', 'price'], 'number'],
            [['availability', 'created_at', 'updated_at'], 'safe'],
            [['property_type', 'room_type', 'preference'], 'string', 'max' => 255],
            [['furnished_status','doorlock_code'], 'string', 'max' => 100],
            [['pe_userid'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['pe_userid' => 'id']],
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
            'user_id' => 'User',
            'agent_id'=>'Agent',
            'property_no'=>'Property No.',
            'pe_userid' => 'Pe Userid',
            'title' => 'Title',
            'description' => 'Description',
            'location' => 'Location',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'property_type' => 'Property Type',
            'room_type' => 'Room Type',
            'preference' => 'Preference',
            'availability' => 'Availability',
            'bedroom' => 'Bedroom',
            'bathroom' => 'Bathroom',
            'carparks' => 'Carparks',
            'furnished_status' => 'Furnished Status',
            'size_of_area' => 'Size Of Area',
            'price' => 'Price',
            'amenities' => 'Amenities',
            'commute' => 'Commute',
            'doorlock_code'=>'Door Lock Code',
            'digital_tenancy' => 'Digital Tenancy',
            'auto_rental' => 'Auto Rental',
            'insurance' => 'Insurance',
            'status' => 'Status',
            'type' => 'Type',
            'is_featured' => 'Featured',
            'pictures'=>'Pictures',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at'=>'Deleted At'
        ];
    }

    /**
     * Gets query for [[Images]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Images::className(), ['property_id' => 'id']);
    }


    public function getPictures()
    {
        return $this->hasOne(Images::className(), ['property_id' => 'id'])->orderBy(['id'=>SORT_ASC]);
    }
    /**
     * Gets query for [[PeUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'pe_userid']);
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

    public function getAgent()
    {
        return $this->hasOne(Users::className(), ['id' => 'agent_id']);
    }

    public function getViews()
    {
        return $this->hasMany(PropertyViews::className(), ['property_id' => 'id']);
    }
    public function getPropertyviews()
    {
        return $this->hasMany(PropertyViews::className(), ['property_id' => 'id']);
    }
    /**
     * Gets query for [[Images]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(BookingRequests::className(), ['id' => 'request_id']);
    }

    public static function checkfavourite($merchant_id,$user_id)
    {
        $favourite_exist = FavouriteProperties::find()->where(['property_id'=>$merchant_id,'user_id'=>$user_id])->one();
        if(!empty($favourite_exist)){
            return true;
        }else{
            return false;
        }
    }
}
