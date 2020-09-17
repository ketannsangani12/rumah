<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_ilifestyle".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $image
 * @property string|null $link
 * @property string|null $description
 * @property string|null $created_at
 */
class Ilifestyle extends \yii\db\ActiveRecord
{
    public $picture;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_ilifestyle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'link','picture', 'description'],'required','on'=>'create'],
            [['title', 'link', 'description'],'required','on'=>'update'],
            [['picture'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg,jpg,png'],
            [['image', 'description'], 'string'],
            [['created_at'], 'safe'],
            [['title', 'link'], 'string', 'max' => 255],
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
            'image' => 'Image',
            'picture'=>'Image',
            'link' => 'Link',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }
}
