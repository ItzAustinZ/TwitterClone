<?php

/* @var $this yii\web\View */

$this->title = 'Site Statistics';

use common\models\User;

function getStartOfMonth($month, $year)
{
    $date = new DateTime("$year-$month-01");
    return $date->getTimestamp();
}
function getEndOfMonth($month, $year)
{
    $nextMonth = $month;
    $nextYear = $year;
    $nextMonth++;
    if($nextMonth > 12)
    {
        $nextMonth -= 12;
        $nextYear++;
    }
    return getStartOfMonth($nextMonth, $nextYear);
}
function getSignupsForMonth($month, $year)
{
    $startOfMonth = getStartOfMonth($month, $year);
    $endOfMonth = getEndOfMonth($month, $year);
    return User::find()->where(['between', 'timestamp', $startOfMonth, $endOfMonth])->count();
}
function getSignupsForIntervalAsArray($startMonth, $startYear, $endMonth, $endYear)
{
    $signupData = array();
    $currentMonth = $startMonth;
    $currentYear = $startYear;
    while(($currentMonth != $endMonth) || ($currentYear != $endYear))
    {
        $signupData[] = ['date' => "$currentYear-$currentMonth", 'signups' => getSignupsForMonth($currentMonth, $currentYear),];
        $currentMonth++;
        if($currentMonth > 12)
        {
            $currentMonth = 1;
            $currentYear++;
        }
    }
    //Get our last result.
    $signupData[] = ['date' => "$currentYear-$currentMonth", 'signups' => getSignupsForMonth($currentMonth, $currentYear),];
    return $signupData;
}

class GraphWrapper
{
    private $data; //Associative array
    private $xkey;
    private $ykeys; //Array of keys located in $data
    private $xLabel;
    private $yLabels; //Array of labels representing the keys in $ykeys
    private $name;
    
    public static function getHeader()
    {
        $header = "";
        $header .= '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">';
        $header .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>';
        $header .= '<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>';
        $header .= '<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>';
        return $header;
    }
    
    public function getChart($heightInPixels = 250)
    {
        $chart = "";
        $height = $heightInPixels . "px";
        $chart .= "<div id='$this->name' style='height: $height;'></div>";
        $chart .= "<script>";
        $chart .= "Morris.Line({";
        $chart .= "element: '$this->name',";
        $chart .= "data: $this->data,";
        $chart .= "xkey: '$this->xkey',";
        $chart .= "ykeys: " . $this->getArrayAsString($this->ykeys) . ",";
        $chart .= "labels: " . $this->getArrayAsString($this->yLabels) . ",";
        $chart .= "xLabels: '$this->xLabel',";
        $chart .= "});";
        $chart .= "</script>";
        return $chart;
    }
        public function getArrayAsString($myArray)
    {
        $yKeyString = "";
        $yKeyString .= "[";
        foreach($myArray as $key)
        {
            if($yKeyString != "[")
            {
                $yKeyString .= ",";
            }
            $yKeyString .= "'$key'";
        }
        $yKeyString .= "]";
        return $yKeyString;
    }
    
    //Pass our mysql query.
    function __construct($element, $dataArray, $xColumnName, $yColumnNames, $myXLabel, $myYLabels) {
        $this->data = json_encode($dataArray);
        $this->name = $element;
        $this->xkey = $xColumnName;
        $this->ykeys = $yColumnNames;
        $this->xLabel = $myXLabel;
        $this->yLabels = $myYLabels;
    }
}


?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Signups Per Month</h1>
        <?php
            //Get our current month and year.
            $endMonth = intval(date('m'));
            $endYear = intval(date('Y'));
            $startMonth = $endMonth;
            $startYear = $endYear - 1;
            
            //Draw our graph.
            $data = getSignupsForIntervalAsArray($startMonth, $startYear, $endMonth, $endYear);
            $graph = new GraphWrapper("signupsOverYear", $data, 'date', ['signups'], 'date', ['Signups']);
            echo $graph->getChart();
        ?>
    </div>
    
    <div class="jumbotron">
        <h1>Top Users</h1>

        <p class="lead">display something here.</p>
        <p>graph here</p>

    </div>
    <?php
        echo "Test Section<br/>";
        echo print_r(getSignupsForIntervalAsArray(01, 2016, 02, 2016));
    ?>
</div>
