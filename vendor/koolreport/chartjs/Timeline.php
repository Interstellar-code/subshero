<?php

namespace koolreport\chartjs;
use \koolreport\core\Utility as Util;

class Timeline extends Chart
{
    protected $type="timeline";

    protected function onInit()
    {
        parent::onInit();
        if (!in_array("timeline", $this->plugins)) $this->plugins[] = "timeline";
    }

    protected function processOptions()
    {
        $options = [];
        $paramsOptions = $this->options;

        $elements = [
            "showText" => Util::get($paramsOptions, "showText", true),
            "textPadding" => Util::get($paramsOptions, "textPadding", 5),
        ];

        $colorScheme = $this->colorScheme;
        if (is_string($colorScheme[0])) $colorScheme = [ $colorScheme ];
        foreach ($colorScheme as $i => $colorArr) {
            foreach ($colorArr as $j => $color) {
                $colorScheme[$i][$j] = $this->getRgba($color,$this->backgroundOpacity);
            }
        }
        // Util::prettyPrint($colorScheme);
        $colorSchemeJson = json_encode($colorScheme);
        $elements["colorFunction"] = "function(text, data, dataset, index) {
            var colorScheme = $colorSchemeJson;
            var seriesIndex = dataset._meta[0].index;
            var colorArr = colorScheme[seriesIndex % colorScheme.length];
            // console.log(colorScheme, seriesIndex, colorArr);
            return Color(colorArr[index % colorArr.length]);
        }";

        unset($paramsOptions["showText"]);
        unset($paramsOptions["textPadding"]);
        unset($paramsOptions["colorFunction"]);

        $options = $paramsOptions;
        $options["elements"] = $elements;
        
        return $options;
    }

    protected function processData()
    {
        
        $columns = $this->getColumns();
        // echo "columns="; var_dump($columns); echo "<br>";
        // exit;
        $columnKeys = array_keys($columns);
        $timelineLabelCol = $columnKeys[0];
        $startCol = $columnKeys[1];
        $endCol = $columnKeys[2];
        $itemLabelCol = Util::get($columnKeys, 3, '');
        
        $labels = array();
        $datasets = array();

        $this->dataStore->popStart();
        while($row = $this->dataStore->pop())
        {
            $timelineLabel = Util::get($row, $timelineLabelCol, '');
            // echo "timelineLabel="; var_dump($timelineLabel); echo "<br>";
            $labels[$timelineLabel] = true;

            $dataset = Util::get($datasets, [$timelineLabel, 'data'], []);

            $start = Util::get($row, $startCol);
            $end = Util::get($row, $endCol);
            $itemLabel = Util::get($row, $itemLabelCol, '');

            $dataset[] = [$start, $end, $itemLabel];
            $datasets[$timelineLabel]['data'] = $dataset;
        }
        // Util::prettyPrint($labels);
        $labels = array_keys($labels);
        $datasets = array_values($datasets);
        // Util::prettyPrint($labels);
        // Util::prettyPrint($datasets);
        $data["labels"] = $labels;
        $data["datasets"] = $datasets;

        // $data["labels"] = Util::get($this->params, 'labels');
        // $data["datasets"] = Util::get($this->params, 'data');

        return $data;
    }

    protected function onRender()
    {
        $data = $this->processData();
        if (count($data) > 0) {
            $settings = array();
            $settings["type"] = $this->type;
            $settings["data"] = $data;
            $settings["options"] = $this->processOptions();
            // $settings["cKeys"] = array_keys($this->getColumns());
            if($this->plugins) {
                $settings["plugins"] = $this->registerPlugins();
            }
        
            $this->template("Chart", array(
                "settings" => $settings,
            ));
        } else {
            $this->template("NoData");
        }
    }
}