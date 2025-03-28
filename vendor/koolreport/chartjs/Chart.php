<?php

namespace koolreport\chartjs;

use \koolreport\core\DataStore;
use \koolreport\core\Utility;
use \koolreport\core\Widget;

class Chart extends Widget
{
    protected $type;

    protected $columns;
    protected $options;
    protected $width;
    protected $height;
    protected $title;
    protected $backgroundOpacity = 0.5;
    protected $clientEvents;
    protected $colorScheme;
    protected $plugins;

    public function version()
    {
        return "3.0.0-dev";
    }

    protected function resourceSettings()
    {
        $maps = array(
            "annotation"=>array(
                "chartjs-plugin-annotation.min.js"
            ),
            "datalabels"=>array(
                "chartjs-plugin-datalabels.min.js"
            ),
            "draggable"=>array(
                "chartjs-plugin-draggable.min.js"
            ),
            "stacked100"=>array(
                array("chartjs-plugin-stacked100.js")
            ),
            "waterfall"=>array(
                "chartjs-plugin-waterfall.min.js"
            ),
            "zoom"=>array(
                "chartjs-plugin-zoom.min.js"
            ),
            "barFunnel"=>array(
                "Chart.BarFunnel.min.js"
            ),
            "linearGauge"=>array(
                "Chart.LinearGauge.js"
            ),
            "smith"=>array(
                "Chart.Smith.js"
            ),
            "timeline"=>array(
                "moment.min.js", array("timeline.min.js")
            )
        );

        $jsPlugins = [];
        if($this->plugins)
        {
            foreach($this->plugins as $name)
            {
                if(is_string($name) && isset($maps[$name]))
                {
                    foreach($maps[$name] as $jsfile)
                    {
                        array_push($jsPlugins,$jsfile);
                    }
                }
            }
        }
        $js = array("Chart.bundle.min.js", "chartjs.js", $jsPlugins);
        
        return array(
            "folder" => "clients",
            "js" => $js
            //"css"=>array("Chart.min.css") // Use for CSP strict mode
        );
    }

    protected function onInit()
    {
        $this->useAutoName("chartjs");
        $this->useDataSource();

        if ($this->type == null) {
            $this->type = Utility::get($this->params, "type");
        }
        if ($this->type == null) {
            throw new \Exception("No chart type defined");
        }

        if ($this->dataStore == null) {
            $data = Utility::get($this->params, "data");
            if (is_array($data)) {
                if (count($data) > 0) {
                    $this->dataStore = new DataStore;
                    $this->dataStore->data($data);
                    $row = $data[0];
                    $meta = array("columns" => array());
                    foreach ($row as $cKey => $cValue) {
                        $meta["columns"][$cKey] = array(
                            "type" => Utility::guessType($cValue),
                        );
                    }
                    $this->dataStore->meta($meta);
                } else {
                    $this->dataStore = new DataStore;
                    $this->dataStore->data(array());
                    $metaColumns = array();
                    foreach ($this->columns as $cKey => $cValue) {
                        if (gettype($cValue) == "array") {
                            $metaColumns[$cKey] = $cValue;
                        } else {
                            $metaColumns[$cValue] = array();
                        }
                    }
                    $this->dataStore->meta(array("columns" => $metaColumns));
                }
            }
            if ($this->dataStore == null) {
                throw new \Exception("dataSource is required for Table");
                return;
            }
        }

        $this->columns = Utility::get($this->params, "columns");
        $this->options = Utility::get($this->params, "options", array());
        $this->plugins = Utility::get($this->params, "plugins", array());
        $this->width = Utility::get($this->params, "width");
        $this->height = Utility::get($this->params, "height");
        $this->title = Utility::get($this->params, "title");
        $this->backgroundOpacity = Utility::get($this->params, "backgroundOpacity", 0.5);
        $this->clientEvents = Utility::get($this->params, "clientEvents", array());

        //Color Scheme
        $this->colorScheme = Utility::get($this->params, "colorScheme");
        if (!is_array($this->colorScheme)) {
            $theme = $this->getReport()->getTheme();
            if ($theme) {
                $theme->applyColorScheme($this->colorScheme);
            }
        }
        if (!is_array($this->colorScheme)) {
            $this->colorScheme = $this->getDefaultScheme();
        }

        if ($this->title) {
            $this->options["title"] = Utility::get($this->options, "title", array());

            $this->options["title"] = array_merge($this->options["title"], array(
                "text" => $this->title,
                "display" => true,
            ));
        }
    }

    protected function getColumns()
    {
        $columnsMeta = $this->dataStore->meta()["columns"];
        $columns = array();
        if ($this->columns == null) {
            $this->columns = array_keys($columnsMeta);
        }
        foreach ($this->columns as $cKey => $cSettings) {
            if (gettype($cSettings) == "array") {
                $columns[$cKey] = array_merge($columnsMeta[$cKey], $cSettings);
            } else {
                $columns[$cSettings] = $columnsMeta[$cSettings];
            }
        }
        return $columns;
    }

    protected function processData()
    {
        //Overrite at decendence
        return array();
    }

    protected function processOptions()
    {
        return $this->options;
    }

    protected function registerPlugins()
    {
        $mapVaribles = array(
            "datalabels"=>"ChartDataLabels",
        );
        $list = array();
        foreach($this->plugins as $name)
        {
            if(is_string($name) && isset($mapVaribles[$name]))
            {
                array_push($list,$mapVaribles[$name]);
            }
        }
        return "function(){return [".implode(",",$list)."];}()";
    }

    protected function getDefaultScheme()
    {
        return array(
            "#3366CC",
            "#DC3912",
            "#FF9900",
            "#109618",
            "#990099",
            "#3B3EAC",
            "#0099C6",
            "#DD4477",
            "#66AA00",
            "#B82E2E",
            "#316395",
            "#994499",
            "#22AA99",
            "#AAAA11",
            "#6633CC",
            "#E67300",
            "#8B0707",
            "#329262",
            "#5574A6",
            "#3B3EAC",
            "#ff6384",
            "#ff9f40",
            "#ffcd56",
            "#4bc0c0",
            "#36a2eb",
            "#9966ff",
            "#c9cbcf",
        );
    }
    protected function getColor($index)
    {
        return $this->colorScheme[$index % count($this->colorScheme)];
    }

    protected function getRgba($color, $opacity = 0.5)
    {
        $colorMaps  =  array(
            'aliceblue'=>'F0F8FF',
            'antiquewhite'=>'FAEBD7',
            'aqua'=>'00FFFF',
            'aquamarine'=>'7FFFD4',
            'azure'=>'F0FFFF',
            'beige'=>'F5F5DC',
            'bisque'=>'FFE4C4',
            'black'=>'000000',
            'blanchedalmond '=>'FFEBCD',
            'blue'=>'0000FF',
            'blueviolet'=>'8A2BE2',
            'brown'=>'A52A2A',
            'burlywood'=>'DEB887',
            'cadetblue'=>'5F9EA0',
            'chartreuse'=>'7FFF00',
            'chocolate'=>'D2691E',
            'coral'=>'FF7F50',
            'cornflowerblue'=>'6495ED',
            'cornsilk'=>'FFF8DC',
            'crimson'=>'DC143C',
            'cyan'=>'00FFFF',
            'darkblue'=>'00008B',
            'darkcyan'=>'008B8B',
            'darkgoldenrod'=>'B8860B',
            'darkgray'=>'A9A9A9',
            'darkgreen'=>'006400',
            'darkgrey'=>'A9A9A9',
            'darkkhaki'=>'BDB76B',
            'darkmagenta'=>'8B008B',
            'darkolivegreen'=>'556B2F',
            'darkorange'=>'FF8C00',
            'darkorchid'=>'9932CC',
            'darkred'=>'8B0000',
            'darksalmon'=>'E9967A',
            'darkseagreen'=>'8FBC8F',
            'darkslateblue'=>'483D8B',
            'darkslategray'=>'2F4F4F',
            'darkslategrey'=>'2F4F4F',
            'darkturquoise'=>'00CED1',
            'darkviolet'=>'9400D3',
            'deeppink'=>'FF1493',
            'deepskyblue'=>'00BFFF',
            'dimgray'=>'696969',
            'dimgrey'=>'696969',
            'dodgerblue'=>'1E90FF',
            'firebrick'=>'B22222',
            'floralwhite'=>'FFFAF0',
            'forestgreen'=>'228B22',
            'fuchsia'=>'FF00FF',
            'gainsboro'=>'DCDCDC',
            'ghostwhite'=>'F8F8FF',
            'gold'=>'FFD700',
            'goldenrod'=>'DAA520',
            'gray'=>'808080',
            'green'=>'008000',
            'greenyellow'=>'ADFF2F',
            'grey'=>'808080',
            'honeydew'=>'F0FFF0',
            'hotpink'=>'FF69B4',
            'indianred'=>'CD5C5C',
            'indigo'=>'4B0082',
            'ivory'=>'FFFFF0',
            'khaki'=>'F0E68C',
            'lavender'=>'E6E6FA',
            'lavenderblush'=>'FFF0F5',
            'lawngreen'=>'7CFC00',
            'lemonchiffon'=>'FFFACD',
            'lightblue'=>'ADD8E6',
            'lightcoral'=>'F08080',
            'lightcyan'=>'E0FFFF',
            'lightgoldenrodyellow'=>'FAFAD2',
            'lightgray'=>'D3D3D3',
            'lightgreen'=>'90EE90',
            'lightgrey'=>'D3D3D3',
            'lightpink'=>'FFB6C1',
            'lightsalmon'=>'FFA07A',
            'lightseagreen'=>'20B2AA',
            'lightskyblue'=>'87CEFA',
            'lightslategray'=>'778899',
            'lightslategrey'=>'778899',
            'lightsteelblue'=>'B0C4DE',
            'lightyellow'=>'FFFFE0',
            'lime'=>'00FF00',
            'limegreen'=>'32CD32',
            'linen'=>'FAF0E6',
            'magenta'=>'FF00FF',
            'maroon'=>'800000',
            'mediumaquamarine'=>'66CDAA',
            'mediumblue'=>'0000CD',
            'mediumorchid'=>'BA55D3',
            'mediumpurple'=>'9370D0',
            'mediumseagreen'=>'3CB371',
            'mediumslateblue'=>'7B68EE',
            'mediumspringgreen'=>'00FA9A',
            'mediumturquoise'=>'48D1CC',
            'mediumvioletred'=>'C71585',
            'midnightblue'=>'191970',
            'mintcream'=>'F5FFFA',
            'mistyrose'=>'FFE4E1',
            'moccasin'=>'FFE4B5',
            'navajowhite'=>'FFDEAD',
            'navy'=>'000080',
            'oldlace'=>'FDF5E6',
            'olive'=>'808000',
            'olivedrab'=>'6B8E23',
            'orange'=>'FFA500',
            'orangered'=>'FF4500',
            'orchid'=>'DA70D6',
            'palegoldenrod'=>'EEE8AA',
            'palegreen'=>'98FB98',
            'paleturquoise'=>'AFEEEE',
            'palevioletred'=>'DB7093',
            'papayawhip'=>'FFEFD5',
            'peachpuff'=>'FFDAB9',
            'peru'=>'CD853F',
            'pink'=>'FFC0CB',
            'plum'=>'DDA0DD',
            'powderblue'=>'B0E0E6',
            'purple'=>'800080',
            'red'=>'FF0000',
            'rosybrown'=>'BC8F8F',
            'royalblue'=>'4169E1',
            'saddlebrown'=>'8B4513',
            'salmon'=>'FA8072',
            'sandybrown'=>'F4A460',
            'seagreen'=>'2E8B57',
            'seashell'=>'FFF5EE',
            'sienna'=>'A0522D',
            'silver'=>'C0C0C0',
            'skyblue'=>'87CEEB',
            'slateblue'=>'6A5ACD',
            'slategray'=>'708090',
            'slategrey'=>'708090',
            'snow'=>'FFFAFA',
            'springgreen'=>'00FF7F',
            'steelblue'=>'4682B4',
            'tan'=>'D2B48C',
            'teal'=>'008080',
            'thistle'=>'D8BFD8',
            'tomato'=>'FF6347',
            'turquoise'=>'40E0D0',
            'violet'=>'EE82EE',
            'wheat'=>'F5DEB3',
            'white'=>'FFFFFF',
            'whitesmoke'=>'F5F5F5',
            'yellow'=>'FFFF00',
            'yellowgreen'=>'9ACD32'
        );
        if (isset($colorMaps[$color])) $color = $colorMaps[$color];
        if (strlen($color) === 7 && substr($color, 0, 1) === "#") {
            $color = substr($color, 1);
        }
        if (strlen($color) === 6 && ctype_xdigit($color)) {
            list($r, $g, $b) = sscanf($color, "%02x%02x%02x");
            $alpha = 1 - $opacity;
            return "rgba($r,$g,$b,$alpha)";
        } else {
            return $color;
        }
    }

    protected function formatValue($value, $format, $row = null)
    {
        $formatValue = Utility::get($format, "formatValue", null);

        if (is_string($formatValue)) {
            eval('$fv="' . str_replace('@value', '$value', $formatValue) . '";');
            return $fv;
        } else if (is_callable($formatValue)) {
            return $formatValue($value, $row);
        } else {
            return Utility::format($value, $format);
        }
    }

    protected function onRender()
    {
        if ($this->dataStore->countData() > 0) {
            $settings = array();
            $settings["type"] = $this->type;
            $settings["data"] = $this->processData();
            $settings["options"] = $this->processOptions();
            $settings["cKeys"] = array_keys($this->getColumns());
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
