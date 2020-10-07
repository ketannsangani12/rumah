<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_workers".
 *
 * @property int $id
 * @property int|null $vendor_id
 * @property string|null $full_name
 * @property string|null $document_no
 * @property string|null $contact_no
 * @property string|null $created_at
 */
class Workers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_workers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name', 'document_no','contact_no'], 'required'],
            [['vendor_id'], 'integer'],
            [['created_at'], 'safe'],
            [['full_name', 'document_no'], 'string', 'max' => 255],
            [['contact_no'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vendor_id' => 'Vendor ID',
            'full_name' => 'Full Name',
            'document_no' => 'Document No',
            'contact_no' => 'Contact No',
            'created_at' => 'Created At',
        ];
    }
}
