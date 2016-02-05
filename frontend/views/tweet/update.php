<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tweet */

$this->title = 'Update Tweet: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tweets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tweet-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
