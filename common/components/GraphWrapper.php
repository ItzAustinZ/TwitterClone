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