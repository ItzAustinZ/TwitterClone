<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tweet".
 *
 * @property integer $id
 * @property string $owner
 * @property string $key
 * @property string $text
 * @property integer $timestamp
 */
class Tweet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tweet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner', 'timestamp'], 'required'],
            [['text'], 'string'],
            [['timestamp'], 'integer'],
            [['owner', 'key'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner' => 'Owner',
            'key' => 'Key',
            'text' => 'Text',
            'timestamp' => 'Timestamp',
        ];
    }
}
