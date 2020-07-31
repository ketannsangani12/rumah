<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "rumah_servicerequest_images".
 *
 * @property int $id
 * @property int|null $service_request_id
 * @property string|null $description
 * @property string|null $image
 * @property string|null $reftype
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property ServiceRequests $serviceRequest
 */
class ServicerequestImages extends \yii\db\ActiveRecord
{
    public $images;
    public $images_array;
    public $images1;
    public $images_array1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_servicerequest_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['images'], 'required','on'=>'create','message' => 'You must upload atleast one image'],
            [['images'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg,jpg,png'],
            [['images1'], 'required','on'=>'create','message' => 'You must upload atleast one image'],
            [['images1'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg,jpg,png'],

            [['service_request_id'], 'integer'],
            [['description', 'reftype'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['image'], 'string', 'max' => 255],
            [['service_request_id'], 'exist', 'skipOnError' => true, 'targetClass' => ServiceRequests::className(), 'targetAttribute' => ['service_request_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'service_request_id' => 'Service Request ID',
            'description' => 'Description',
            'image' => 'Image',
            'images'=>'Images',
            'images1'=>'Images1',
            'reftype' => 'Reftype',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[ServiceRequest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServiceRequest()
    {
        return $this->hasOne(ServiceRequests::className(), ['id' => 'service_request_id']);
    }

    public static function SaveTempAttachments($attachments) {
        $files = "";
        $allwoedFiles = ['jpg', 'png'];
        if ($_FILES) {
            $tmpname = $_FILES['Images']['tmp_name']['images'];
            $fname = $_FILES['Images']['name']['images'];

            if (!empty($attachments)) {
                if (count($fname) > 0) {
                    //Loop through each file
                    for ($i = 0; $i < count($fname); $i++) {
                        //Get the temp file path
                        $tmpFilePath = $tmpname[$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "") {
                            //save the filename
                            $shortname = $fname[$i];
                            $size = $attachments['Images']['size']['images'][$i];
                            $ext = substr(strrchr($shortname, '.'), 1);
                            if (in_array($ext, $allwoedFiles)) {
                                //save the url and the file
                                $newFileName = Yii::$app->security->generateRandomString(40) . "." . $ext;
                                //Upload the file into the temp dir
                                if (move_uploaded_file($tmpFilePath, 'uploads/servicerequestimages/temp/' . $newFileName)) {
                                    $files['initialPreview'] = Url::base(TRUE) . '/uploads/servicerequestimages/temp/' . $newFileName;
                                    $files['initialPreviewAsData'] = true;
                                    // $files['uploadExtraData'][]['is_post'] = 'new';
                                    $files['initialPreviewConfig'][]['key'] = $newFileName;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $files;
    }
}
