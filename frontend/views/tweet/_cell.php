<?php
/* 
 * Pass a tweet to $model.
 */
use app\models\MediaConnections;
use yii\helpers\Html;
?>

<div class="col-md-6 well">
<?php
    echo "<div class='row-fluid'>"; 
        echo "<div class='col-md-3'>";
            echo Html::a($model->owner, ["/tweet/view-user", 'username' => $model->owner]);
            echo "<br/>";
            $hash = hash("md5", $model->owner);;
            echo Html::a("<img src='http://www.gravatar.com/avatar/$hash?d=identicon' />", ["/tweet/view-user", 'username' => $model->owner]);
        echo "</div>";
        echo "<div class='col-md-9 text-center'>";
            echo "<div class='panel panel-default'>";
                echo "<div class='panel-body'>" . $model->getText() . "</div>";
                //Get images.
                $mediaConnections = MediaConnections::find()->where(['tweet' => $model->id,])->all();
                foreach($mediaConnections as $image)
                {
                    echo "<img class='img-thumbnail' src='$image->url'/>";
                }
            echo "</div>";
        echo "</div>";
    echo "</div>";
    
    echo "<div class='row-fluid'>";
        echo "<div class='col-md-12'>";
            echo date("Y-m-d H:i:s", $model->timestamp);
        echo "</div>";
    echo "</div>"
?>
</div>
