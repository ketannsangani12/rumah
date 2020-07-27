<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rumah_platform_fees".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $platform_fees
 * @property string $other
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class PlatformFees extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_platform_fees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','platform_fees','other'],'required','on'=>'create'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['platform_fees', 'other'], 'string', 'max' => 10],
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
            'platform_fees' => 'Platform Fees (%)',
            'other' => 'Other (%)',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
