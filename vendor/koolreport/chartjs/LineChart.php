<?php

namespace koolreport\chartjs;
use \koolreport\core\Utility as Util;

class LineChart extends Chart
{
    protected $type = "line";
    protected $chartSettings = [
        "chartType", "axis",
        "backgroundColor", "borderCapStyle", "borderColor", "borderDash", "borderDashOffset",
        "borderJoinStyle", "borderWidth", "cubicInterpolationMode", "clip", "fill", "hoverBackgroundColor",
        "hoverBorderCapStyle", "hoverBorderColor", "hoverBorderDash", "hoverBorderDashOffset",
        "hoverBorderJoinStyle", "hoverBorderWidth", "label", "lineTension", "order", "pointBackgroundColor",
        "pointBorderColor", "pointBorderWidth", "pointHitRadius", "pointHoverBackgroundColor",
        "pointHoverBorderColor", "pointHoverBorderWidth", "pointHoverRadius", "pointRadius", "pointRotation",
        "pointStyle", "showLine", "spanGaps", "steppedLine", "xAxisID", "yAxisID"
    ];

    protected function processOptions()
    {
        $options = $this->options;

        $axes = Util::get($this->params, "axes", []);
        foreach ($axes as $k => $v) {
            $axes[$k]["id"] = $k;
        }
        $axes = array_values($axes);
        if (!empty($axes)) {
            $axesKey = "yAxes";
            Util::set($options, ["scales", $axesKey], $axes);
        }

        return $options;
    }

    protected function processData()
    {

        $labels = array();
        $datasets = array();

        $columns = $this->getColumns();
        $columnKeys = array_keys($columns);
        for($i=1;$i<count($columnKeys);$i++)
        {
            $cSettings = Util::get($columns,$columnKeys[$i]);
            $dataset = array(
                "label"=> Util::get($cSettings,"label",$columnKeys[$i]),
                "fill"=>false,
                "borderColor"=>$this->getColor($i-1),
                "backgroundColor"=>$this->getColor($i-1),
                "data"=>array(),
                "fdata"=>array(),
            );

            foreach ($cSettings as $k => $v) {
                if (!in_array($k, $this->chartSettings)) continue;
                if ($k === "chartType") $k = "type";
                if ($k === "axis") $k = "yAxisID";
                $dataset[$k] = $v;
            }

            $config = Util::get($cSettings,"config");
            if($config!==null)
            {
                $dataset = array_merge($dataset,$config);
            }      
            array_push($datasets,$dataset);
        }

        $this->dataStore->popStart();
        while($row = $this->dataStore->pop())
        {
            array_push($labels,$row[$columnKeys[0]]);
            for($i=1;$i<count($columnKeys);$i++)
            {
                array_push($datasets[$i-1]["data"],$row[$columnKeys[$i]]);
                array_push($datasets[$i-1]["fdata"],$this->formatValue($row[$columnKeys[$i]],$columns[$columnKeys[$i]],$row));
            }
        }
        $data = array(
            "labels"=>$labels,
            "datasets"=>$datasets,
        );
        return $data;
    }
}