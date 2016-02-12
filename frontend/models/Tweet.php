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
        if(($this->key == "") || ($this->key == null))
        {
            return $this->text;
        }
        elseif(Yii::$app->user->isGuest)
        {
            return $this->getEncodedText();
        }
        $userIsOwner = Yii::$app->user->identity->username == $this->owner;
        $user = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
        $userHasKey = $user->hasKey($this->key);
        if($userIsOwner || $userHasKey)
        {
            return $this->text;
        }
        else
        {
            return $this->getEncodedText();
        }
    }
    
    //Returns our encoded text.
    private function getEncodedText()
    {
        $currentKeyIndex = 0;
        $currentTextIndex = 0;
        $spacingTimer = 0;
        $encryptedString = "";
        //Loop through each letter in the text. Modify that letter by our current key index.
        while($currentTextIndex < strlen($this->text))
        {
            //Get our character value offset by 32 (the ascii value of space). This way, a space has an offset value of 0.
            $keyCharacterValue = ord($this->key[$currentKeyIndex]) - 32;
            //Get our new text value.
            $textCharacterValue = ord($this->text[$currentTextIndex]) + $keyCharacterValue;
            //Check if we need to wrap our value around.
            if($textCharacterValue >= 127)
            {
                $textCharacterValue = 32 + ($textCharacterValue - 127);
            }
            //Append our character.
            $encryptedString .= chr($textCharacterValue);
            //Should we put a space?
            $spacingTimer++;
            if($spacingTimer == 2)
            {
                $spacingTimer = 0;
                $encryptedString .= " ";
            }
            //Iterate both indexes.
            $currentKeyIndex++;
            if($currentKeyIndex >= strlen($this->key))
            {
                $currentKeyIndex = 0;
            }
            $currentTextIndex++;
        }
        return $encryptedString;
    }
}
