<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\KeyConnections */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="key-connections-form">

    <?php $form = ActiveForm::begin(['action' => '@web/key-connections/create']); ?>

    <?php echo "Key Name: " . $form->field($model, 'name')->textInput(['maxLength' => true,]); ?>
    <?php echo "Key Value: " . $form->field($model, 'text')->textInput(['maxlength' => true,]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
