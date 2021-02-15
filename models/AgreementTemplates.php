<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_agreement_templates".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $document
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class AgreementTemplates extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_agreement_templates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','document'],'required','on'=>'create'],
            [['name'],'required','on'=>'update'],
            //[['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'doc,docx'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'document' => 'Content',
            'file' => 'File',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
