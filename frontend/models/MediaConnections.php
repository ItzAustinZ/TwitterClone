<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "media_connections".
 *
 * @property integer $id
 * @property integer $tweet
 * @property string $url
 * @property integer $timestamp
 */
class MediaConnections extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media_connections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tweet', 'timestamp'], 'integer'],
            [['timestamp'], 'required'],
            [['url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tweet' => 'Tweet',
            'url' => 'Url',
            'timestamp' => 'Timestamp',
        ];
    }
}
