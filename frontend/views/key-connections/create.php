<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\KeyConnections */

$this->title = 'Create Key Connections';
$this->params['breadcrumbs'][] = ['label' => 'Key Connections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="key-connections-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
