<?php

namespace app\models;

use Yii;

use common\models\User;

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
    public $image;
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
            [['text', 'key'], 'string'],
            [['timestamp'], 'integer'],
            [['image'], 'file', 'extensions' => 'jpg, gif, png', 'maxFiles' => 10],
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
    
    public static function generateTestTweet($username, $id)
    {
        $model = new Tweet();
        $model->key = $id;
        $model->owner = $username;
        $model->text = "message #$id";
        $month = rand(1,12);
        $day = rand(1,28);
        $year = 2015;
        $date = new \DateTime("$year-$month-$day");
        $model->timestamp = $date->getTimestamp();
        $model->save(false);

    }
    
    //Returns text appropriate for the user.
    public function getText()
    {
        $userIsOwner = Yii::$app->user->identity->username == $this->owner;
        $user = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
        $userHasKey = $user->hasKey($this->key);
        if($userIsOwner || $userHasKey)
        {
            return $this->text;
        }
        else
        {
            return "Doesn't have key - IMPLEMENT ENCRYPTION";
        }
    }
}
