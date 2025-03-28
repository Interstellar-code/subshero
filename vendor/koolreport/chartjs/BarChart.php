<?php

namespace koolreport\chartjs;

use \koolreport\core\Utility as Util;

class BarChart extends Chart
{
    protected $type = "horizontalBar";
    protected $stacked = false;
    protected $chartSettings = [
        "chartType", "axis",
        "backgroundColor", "borderColor", "borderSkipped", "borderWidth", "data", "hoverBackgroundColor", 
        "hoverBorderColor", "hoverBorderWidth", "label", "order", "xAxisID", "yAxisID"
    ];

    protected function onInit()
    {
        parent::onInit();
        $this->stacked = Util::get($this->params, "stacked", false);
    }

    protected function processOptions()
    {
        $options = $this->options;

        if ($this->stacked) {
            $options["scales"] = Util::get($options, "scales", array());
            $options["scales"]["yAxes"] = Util::get($options["scales"], "yAxes", array(array()));
            foreach ($options["scales"]["yAxes"] as &$axis) {
                $axis["stacked"] = true;
            }
            $options["scales"]["xAxes"] = Util::get($options["scales"], "xAxes", array(array()));
            foreach ($options["scales"]["xAxes"] as &$axis) {
                $axis["stacked"] = true;
            }
        }

        $axes = Util::get($this->params, "axes", []);
        foreach ($axes as $k => $v) {
            $axes[$k]["id"] = $k;
        }
        $axes = array_values($axes);
        if (!empty($axes)) {
            $axesKey = $this->type === "bar" ? "yAxes" : "xAxes";
            $options["scales"][$axesKey] = $axes;
        }

        return $options;
    }

    protected function processData()
    {

        $columns = $this->getColumns();
        $columnKeys = array_keys($columns);

        $labels = array();
        $datasets = array();
        for ($i = 1; $i < count($columnKeys); $i++) {
            $cSettings = Util::get($columns, $columnKeys[$i]);
            $dataset = array(
                "label" => Util::get($cSettings, "label", $columnKeys[$i]),
                "borderWidth" => 1,
                "borderColor" => $this->getColor($i - 1),
                "backgroundColor" => $this->getRgba($this->getColor($i - 1), $this->backgroundOpacity),
                "data" => array(),
                "fdata" => array(),
            );
            // $backgroundColorArray = Util::get($this->params, "backgroundColorArray");
            // if (isset($backgroundColorArray)) $dataset["backgroundColor"] = $backgroundColorArray;

            foreach ($cSettings as $k => $v) {
                if (!in_array($k, $this->chartSettings)) continue;
                if ($k === "chartType") $k = "type";
                if ($k === "axis") {
                    $k = $this->type === "bar" ? "yAxisID" : "xAxisID";
                }
                $dataset[$k] = $v;
            }

            $config = Util::get($cSettings, "config");
            if ($config !== null) {
                $dataset = array_merge($dataset, $config);
            }
            array_push($datasets, $dataset);
        }

        $this->dataStore->popStart();
        while ($row = $this->dataStore->pop()) {
            array_push($labels, $row[$columnKeys[0]]);
            for ($i = 1; $i < count($columnKeys); $i++) {
                array_push($datasets[$i - 1]["data"], $row[$columnKeys[$i]]);
                array_push($datasets[$i - 1]["fdata"], $this->formatValue($row[$columnKeys[$i]], $columns[$columnKeys[$i]], $row));
            }
        }

        $data = array();
        $data["labels"] = $labels;
        $data["datasets"] = $datasets;
        return $data;
    }
}
