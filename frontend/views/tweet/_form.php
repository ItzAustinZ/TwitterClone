<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Tweet;

use app\models\UploadForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tweet */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-6 well">
<?php
    $form = ActiveForm::begin(['action' => '../tweet/create']);
    echo "<div class='row-fluid'>"; 
        echo "<div class='col-md-3'>";
            echo Yii::$app->user->identity->username;
            echo "<br/>";
            $hash = hash("md5", Yii::$app->user->identity->username);
            echo "<img src='http://www.gravatar.com/avatar/$hash?d=identicon' />";
        echo "</div>";
        echo "<div class='col-md-9 text-center'>";
            echo "<div class='panel panel-default'>";
                echo "<div class='panel-body'>";
                    echo $form->field($model, 'key')->textInput(['maxlength' => true]);
                    echo $form->field($model, 'text')->textarea(['rows' => 6]);
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    
    echo "<div class='row-fluid'>";
        echo "<div class='col-md-12'>";
            echo "OPTIONS GO HERE";
            echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        echo "</div>";
    echo "</div>";
    ActiveForm::end();
?>
</div>
