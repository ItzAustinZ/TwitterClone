<?php
/*
 * Pass an array of Tweet models to $tweets.
 */
?>
        
<div class="body-content">
<?php
$i = 0;
foreach($tweets as $message)
{
    //Open a new row.
    if($i == 0)
    {
        echo "<div class='row-fluid'>";
    }
    echo $this->render('_cell', [
        'model' => $message,
        ]);
    //Close the row.
    if($i == 1)
    {
        echo "</div>";
        $i = -1;
    }
    $i++;
}
//Close our last row if we need to.
if($i != 0)
{
    echo "</div>";
}
?>
</div>
  
