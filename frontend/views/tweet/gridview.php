<?php
/*
 * Pass an array of Tweet models to $tweets.
 */
use app\models\Tweet;
<<<<<<< HEAD
use yii\widgets\LinkPager;
=======
use yii\web\Session;

//Check for view variables.
$session = new Session();
$session->open();
if(!$session->has('eyes_admin'))
    $session['eyes_admin'] = false;
if(!$session->has('eyes_encrypted'))
    $session['eyes_encrypted'] = true;
>>>>>>> feature-toolbar
?>
        
<div class="body-content">
    
<?php
echo $this->render('_toolbar');
$i = 0;
$renderedForm = false;
foreach($tweets as $message)
{
    //Open a new row.
    if($i == 0)
    {
        echo "<div class='row'>";
    }
    if(!$renderedForm && !Yii::$app->user->isGuest)
    {
        if(!isset($model))
        {
            $model = new Tweet();
        }
        $renderedForm = true;
        $i++;
        echo $this->render('_form', ['model' => $model,]);
    }
    //Get our encoding.
    $encoding = $message->getEncodingLevel();
    //Do we display a message with this encoding?
    if(!(($encoding == 2) && !$session['eyes_encrypted']))
    {
        echo $this->render('_cell', [
            'model' => $message, 'encoding' => $encoding,
            ]);
        //Close the row.
        if($i == 1)
        {
            echo "</div>";
            $i = -1;
        }
        $i++;
    }
}
//Close our last row if we need to.
if($i != 0)
{
    echo "</div>";
}

echo LinkPager::widget(['pagination' => $pages,]);
?>
</div>
  
