<?php
/* 
 * Pass a tweet to $model.
 */
use app\models\MediaConnections;
use yii\helpers\Html;
?>

<div class="col-md-6">
<?php
    $panelColor = "#E0FFFF";
    $encryptionStatus = "UNKNOWN_STATUS";
    $displayKey = false;
    switch($encoding)
    {
        case 0: //UNENCODED - UNOWNED
            $panelColor = "#E0FFFF";
            $encryptionStatus = "UNENCODED";
            break;
        case 1: //DECODED - UNOWNED
            $panelColor = "#98FB98";
            $encryptionStatus = "DECODED";
            $displayKey = true;
            break;
        case 2: //ENCODED - UNOWNED
            $panelColor = "#F08080";
            $encryptionStatus = "ENCODED";
            break;
        case 3: //OWNED
            $panelColor = "#FFFF99";
            $encryptionStatus = "OWNED";
            $displayKey = true;
            break;
        case 4: //ADMIN_EYES - UNOWNED
            $panelColor = "#DDA0DD";
            $encryptionStatus = "DECODED (ADMIN EYES)";
            $displayKey = true;
            break;
    }
    echo "<div class='panel panel-default' >";
        echo "<div class='panel-heading' style='background-color: $panelColor;'>";
            echo "<span>" . Html::a($model->owner, ["/tweet/view-user", 'username' => $model->owner]) . "</span>";
            if($displayKey) 
                echo "<span class='pull-right'>" . Html::encode($encryptionStatus . " - " . $model->key) . "</span>";
            else
                echo "<span class='pull-right'>" . $encryptionStatus . "</span>";
        echo "</div>";
        echo "<div class='panel-body'>";
            echo "<div class='row-fluid'>"; 
                echo "<div class='col-md-3'>";
                    echo Html::a($model->owner, ["/tweet/view-user", 'username' => $model->owner]);
                    echo "<br/>";
                    $hash = hash("md5", $model->owner);;
                    echo Html::a("<img src='http://www.gravatar.com/avatar/$hash?d=identicon' />", ["/tweet/view-user", 'username' => $model->owner]);
                echo "</div>";
                echo "<div class='col-md-9 text-center'>";
                    echo "<div class='panel panel-default'>";
                        echo "<div class='panel-body'>" . Html::encode($model->getText($encoding)) . "</div>";
                        //Get images.
                        $mediaConnections = MediaConnections::find()->where(['tweet' => $model->id,])->all();
                        foreach($mediaConnections as $image)
                        {
                            echo "<img class='img-thumbnail' src='$image->url'/>";
                        }
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
        echo "<div class='panel-heading' style='background-color: $panelColor;'>";
            echo date("Y-m-d H:i:s", $model->timestamp);
        echo "</div>";
    echo "</div>";
?>
</div>
