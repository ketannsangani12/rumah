<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_todo_items".
 *
 * @property int $id
 * @property int|null $todo_id
 * @property string|null $description
 * @property float|null $price
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property TodoList $todo
 */
class TodoItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_todo_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description','price'], 'required'],
            [['price'], 'required','on'=>'defectquote'],
            [['todo_id'], 'integer'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
            'price' => 'Price',
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
