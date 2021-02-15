<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tls_cms".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 */
class Cms extends \yii\db\ActiveRecord
{
    public $picture;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_cms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title','content'], 'required'],
            [['picture'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg,jpg,png'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
            'content' => 'Content',
            'picture' => 'Image',
            'image' => 'Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
