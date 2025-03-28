<?php
/**
 * This file contains Area chart widget
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license
 */
namespace koolreport\morris_chart;
use \koolreport\core\Utility as Util;

class Area extends Morris
{
    protected function prepareOptions()
    {
        $columns = $this->getColumnList();
        
        $xKey = null;
        $yKeys = array();
        $labels = array();
        $preUnits = null;
        $postUnits = null;
        foreach($columns as $cKey=>$cSettings)
        {
            if($xKey==null)
            {
                $xKey = $cKey;
            }
            else
            {
                array_push($yKeys,$cKey);
                array_push($labels,Util::get($cSettings,"label",$cKey));
                if($preUnits===null && Util::get($cSettings,"prefix"))
                {
                    $preUnits = Util::get($cSettings,"prefix");
                }
                if($postUnits===null && Util::get($cSettings,"suffix"))
                {
                    $postUnits = Util::get($cSettings,"suffix");
                }                
            }
        }

        $hasFormatValue = false;
        $data = array();
        $this->dataStore->popStart();
        while($row = $this->dataStore->pop())
        {
            $gRow = array();
            foreach($columns as $cKey=>$cSettings)
            {
                $type = Util::get($cSettings,"type","unknown");
                if($type=="number")
                {
                    $decimals = Util::get($cSettings,"decimals");
                    if($decimals!==null)
                    {
                        $gRow[$cKey] = $this->roundNumber($row[$cKey],$decimals);
                    }
                    else
                    {
                        $gRow[$cKey] = $row[$cKey];    
                    }
                }
                else
                {
                    $gRow[$cKey] = Util::format($row[$cKey],$cSettings);
                }

                $formatValue = Util::get($cSettings, "formatValue");
                if (isset($formatValue)) {
                    $hasFormatValue = true;
                    if (is_callable($formatValue)) $fValue = $formatValue($row[$cKey], $row, $cKey);
                    else $fValue = $formatValue;
                    $gRow["{" . $cKey . "_formatValue}"] = $fValue;
                }                
            }
            array_push($data,$gRow);
        }

        $options = array(
            "element"=>$this->chartId,
            "data"=>$data,
            "xkey"=>$xKey,
            "ykeys"=>$yKeys,
            "labels"=>$labels,
            "resize"=>true,
            "parseTime" => false,
            "hasFormatValue" => $hasFormatValue,
        );
        if($preUnits!==null)
            $options["preUnits"] = $preUnits;
        if($postUnits!==null)
            $options["postUnits"] = $postUnits;
        if($this->colorScheme!==null)
        {
            $options["lineColors"] = $this->colorScheme;
        }
        
        $options = $this->buildHoverCallback($options, $columns);

        return array_merge($options,$this->options);
    }
}