<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "key_connections".
 *
 * @property integer $id
 * @property integer $owner
 * @property string $text
 * @property integer $timestamp
 */
class KeyConnections extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'key_connections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner', 'timestamp'], 'integer'],
            [['timestamp'], 'required'],
            [['text'], 'string', 'max' => 255]
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
            'text' => 'Text',
            'timestamp' => 'Timestamp',
        ];
    }
    
    public function getKeyOptionsByUserId($id)
    {
        $keys = array();
        $keys[""] = "NONE";
        $userKeys = KeyConnections::find()->where(['owner' => $id])->all();
        foreach($userKeys as $singleKey)
        {
            $keys[$singleKey->text] = $singleKey->text;
        }
        return $keys;
    }
}
