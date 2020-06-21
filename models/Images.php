<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "rumah_images".
 *
 * @property int $id
 * @property int|null $property_id
 * @property string|null $image
 * @property string|null $created_at
 *
 * @property Properties $property
 */
class Images extends \yii\db\ActiveRecord
{
    public $images;
    public $images_array;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['images'], 'required','on'=>'create','message' => 'You must upload atleast one image'],
            [['property_id'], 'integer'],
            [['images'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg,jpg,png'],
            [['image'], 'string'],
            [['created_at'], 'safe'],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Properties::className(), 'targetAttribute' => ['property_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'property_id' => 'Property ID',
            'image' => 'Image',
            'images'=>'Images',
            'created_at' => 'Created At',
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
                                if (move_uploaded_file($tmpFilePath, 'uploads/products/temp/' . $newFileName)) {
                                    $files['initialPreview'] = Url::base(TRUE) . '/uploads/products/temp/' . $newFileName;
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
