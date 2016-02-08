<?php

/* @var $this yii\web\View */

$this->title = 'Site Statistics';

use common\models\User;
use app\models\Tweet;

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
function getNumTweetsForMonthByUsername($username, $month, $year)
{
    $startOfMonth = getStartOfMonth($month, $year);
    $endOfMonth = getEndOfMonth($month, $year);
    return Tweet::find()->where(['between', 'timestamp', $startOfMonth, $endOfMonth])->andWhere(['owner' => $username])->count();
}
function getNumTweetsForIntervalByUsername($username, $startMonth, $startYear, $endMonth, $endYear)
{
    $startOfInterval = getStartOfMonth($startMonth, $startYear);
    $endOfInterval = getEndOfMonth($endMonth, $endYear);
    return Tweet::find()->where(['between', 'timestamp', $startOfInterval, $endOfInterval])->andWhere(['owner' => $username])->count();
}
function getTopUsersForInterval($startMonth, $startYear, $endMonth, $endYear, $numUsers = 5)
{
    $topUsers = array();
    $allUsers = User::find()->all();
    foreach($allUsers as $user)
    {
        $numTweets = getNumTweetsForIntervalByUsername($user->username, $startMonth, $startYear, $endMonth, $endYear);
        if(count($topUsers) < $numUsers)
        {
            $topUsers[] = array('label' => $user->username, 'value' => $numTweets);
        }
        else
        {
            //Check if we are higher than the lowest user.
            if($topUsers[0]['value'] < $numTweets)
            {
                //If so, replace this user.
                $topUsers[0] = array('label' => $user->username, 'value' => $numTweets);
            }
        }
        //Sort our top users array so lower $numTweets come first.
        $needSort = true;
        while($needSort)
        {
            $needSort = false;
            $i = 0;
            while($i < (count($topUsers)-1))
            {
                if($topUsers[$i]['value'] > $topUsers[$i+1]['value'])
                {
                    $temp = $topUsers[$i];
                    $topUsers[$i] = $topUsers[$i+1];
                    $topUsers[$i+1] = $temp;
                    $needSort = true;
                }
                $i++;
            }
        }
    }
    return $topUsers;
}
function getTopUsers($startMonth, $startYear, $endMonth, $endYear, $numUsers = 5)
{
    $topUsersAsArray = array();
    $topUsers = getTopUsersForInterval($startMonth, $startYear, $endMonth, $endYear, $numUsers);
    foreach($topUsers as $user)
    {
        $topUsersAsArray[] = $user['label'];
    }
    return $topUsersAsArray;
}
function getTopUserTweetsForIntervalAsArray($startMonth, $startYear, $endMonth, $endYear, $numUsers = 5)
{
    $topUsers = getTopUsersForInterval($startMonth, $startYear, $endMonth, $endYear, $numUsers);
    $topUserData = array();
    $currentMonth = $startMonth;
    $currentYear = $startYear;
    while(($currentMonth != $endMonth) || ($currentYear != $endYear))
    {
        $newUserData = ['date' => "$currentYear-$currentMonth",];
        foreach($topUsers as $user)
        {
            $newUserData[$user['label']] = getNumTweetsForMonthByUsername($user['label'], $currentMonth, $currentYear);
        }
        $topUserData[] = $newUserData;
        $currentMonth++;
        if($currentMonth > 12)
        {
            $currentMonth = 1;
            $currentYear++;
        }
    }
    return $topUserData;
}



?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Recent Signups</h1>
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
        <h1>Most Active Users</h1>
        <?php
            //Get our current month and year.
            $endMonth = intval(date('m'));
            $endYear = intval(date('Y'));
            $startMonth = $endMonth;
            $startYear = $endYear - 1;
            
            //Draw our graph.
            $data = getTopUsersForInterval($startMonth, $startYear, $endMonth, $endYear);
            $donut = new GraphWrapper("topusersoveryear", $data, 'date');
            echo $donut->getDonut(325);
        ?>
        <?php
            $data = getTopUserTweetsForIntervalAsArray($startMonth, $startYear, $endMonth, $endYear);
            $multiGraph = new GraphWrapper("topUserActivity", $data, 'date', getTopUsers($startMonth, $startYear, $endMonth, $endYear), 'date', getTopUsers($startMonth, $startYear, $endMonth, $endYear));
            echo $multiGraph->getChart();
        ?>
    </div>
</div>







<?php
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
    
    public function getDonut($heightInPixels = 250)
    {
        $chart = "";
        $height = $heightInPixels . "px";
        $chart .= "<div id='$this->name' style='margin:auto; height: $height; width: $height;'></div>";
        $chart .= "<script>";
        $chart .= "Morris.Donut({";
        $chart .= "element: '$this->name',";
        $chart .= "data: " . str_replace('"value"','value', str_replace('"label"','label', $this->data));
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
    function __construct($element, $dataArray, $xColumnName="", $yColumnNames="", $myXLabel="", $myYLabels="") {
        $this->data = json_encode($dataArray);
        $this->name = $element;
        $this->xkey = $xColumnName;
        $this->ykeys = $yColumnNames;
        $this->xLabel = $myXLabel;
        $this->yLabels = $myYLabels;
    }
}
?>