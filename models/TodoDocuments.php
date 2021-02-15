<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_todo_documents".
 *
 * @property int $id
 * @property int|null $todo_id
 * @property string|null $description
 * @property string|null $document
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property TodoList $todo
 */
class TodoDocuments extends \yii\db\ActiveRecord
{
    public $document_pdf;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_todo_documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description','document_pdf'], 'required'],
            [['document_pdf'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
            [['todo_id'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['document'], 'string', 'max' => 255],
            [['todo_id'], 'exist', 'skipOnError' => true, 'targetClass' => TodoList::className(), 'targetAttribute' => ['todo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'todo_id' => 'Todo ID',
            'description' => 'Description',
            'document_pdf' => 'Document',
            'document' => 'Document',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
}
