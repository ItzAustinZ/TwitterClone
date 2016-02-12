<?php
use yii\web\Session;
use yii\helpers\Html;
use common\models\User;
?>
<div class="navbar navbar-inverse navbar-fixed-bottom" role="navigation">
  <div class="container">
    <div class="navbar-header">

<?php
$session = new Session();
$session->open();
if(!$session->has('eyes_admin'))
    $session['eyes_admin'] = false;
if(!$session->has('eyes_encrypted'))
    $session['eyes_encrypted'] = true;

echo "<div class='btn-group' role='group' aria-label='Eye Options'>";
    if($session['eyes_encrypted'])
        echo Html::a("Display Encrypted", ["/tweet/toggle-encrypted-eyes"], ['class' => 'btn btn-success']);
    else
        echo Html::a("Display Encrypted", ["/tweet/toggle-encrypted-eyes"], ['class' => 'btn btn-default']);
    //Check if we are a admin.
    if(!Yii::$app->user->isGuest && (Yii::$app->user->identity->role >= User::ROLE_ADMIN))
    {
        if($session['eyes_admin'])
            echo Html::a("Admin Eyes", ["/tweet/toggle-admin-eyes"], ['class' => 'btn btn-success']);
        else
            echo Html::a("Admin Eyes", ["/tweet/toggle-admin-eyes"], ['class' => 'btn btn-default']);
    }
echo "</div>";
?>

    </div>
  </div>
</div>