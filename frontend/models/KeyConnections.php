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
 * @property string $name
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
            [['timestamp', 'name', 'text'], 'required'],
            [['name'], 'string'],
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
            'name' => 'Name',
        ];
    }
    
    public function getKeyOptionsByUserId($id)
    {
        $keyOptions = array();
        $keyOptions[""] = "None";
        $allKeyConnections = KeyConnections::find()->where(['owner' => $id])->all();
        foreach($allKeyConnections as $connection)
        {
            if($connection->text != "")
            {
                $keyOptions[$connection->text] = $connection->name . " - " . $connection->text;
            }
        }
        return $keyOptions;
    }
}
