<?php
/**
 * This file contains Donut chart widget
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license
 */
namespace koolreport\morris_chart;
use \koolreport\core\Utility as Util;

class Donut extends Morris
{
	protected $showPercentage = false;
	protected $decimals = 0;

	protected function onInit()
	{
		parent::onInit();
		$this->showPercentage = Util::get($this->params,"showPercentage",false);
		$this->decimals = Util::get($this->params,"decimals");
	}

    protected function prepareOptions()
    {
		$columns = $this->getColumnList();
		
		$labelKey = null;
		$valueKey = null;

		foreach($columns as $cKey=>$cSettings)
		{
			if($labelKey===null)
			{
				$labelKey = $cKey;
			}
			else if($valueKey===null)
			{
				$valueKey = $cKey;
			}
			else
			{
				break;
			}
		}

		$hasFormatValue = false;
        $data = array();
        $total = 0;
		$this->dataStore->popStart();
		$cKey = $valueKey;
		$cSettings = $columns[$cKey];
		$formatValue = Util::get($cSettings, "formatValue");
        while($row = $this->dataStore->pop())
		{
			$gRow = array(
				"label"=>Util::format($row[$labelKey],$columns[$labelKey]),
				"value"=>$row[$valueKey]
			);
			$total+=$row[$valueKey];

			if (isset($formatValue) && !$this->showPercentage) {
				$hasFormatValue = true;
				if (is_callable($formatValue)) $fValue = $formatValue($row[$cKey], $row, $cKey);
				else $fValue = $formatValue;
				$gRow["{formatValue}"] = $fValue;
			}

			array_push($data,$gRow);
		}
		if($this->showPercentage)
		{
			for($i=0;$i<count($data);$i++)
			{
				$value = $data[$i]["value"]*100/$total;
				$data[$i]["value"] = $this->roundNumber($value,($this->decimals!==null)?$this->decimals:0);
				if (isset($formatValue)) {
					$data[$i]["{formatValue}"] = $formatValue($value, $data[$i], $cKey);
				}
			}
		}
		else
		{
			$decimals = Util::get($columns[$valueKey],"decimals");
			if($decimals===null)
			{
				$decimals = $this->decimals;
			}
			if($decimals!==null)
			{
				for($i=0;$i<count($data);$i++)
				{
					$data[$i]["value"] = $this->roundNumber($data[$i]["value"],$decimals);
				}			
			}
		}

		$options = array(
			"resize"=>true,
			"element"=>$this->chartId,
			"data"=>$data,
		);

		if($this->colorScheme)
		{
			$options["colors"] = $this->colorScheme;
		}
		
		$hasFormatterTemplate = Util::get($this->params, "formatterTemplate");
		$formatterTemplate = Util::get($this->params, "formatterTemplate", "{formatValue}");
		if($this->showPercentage || $hasFormatValue || $hasFormatterTemplate)
		{
			$options["formatter"]="function(y,data){ 
				// console.log(y, data); 
				var formatValue = data['{formatValue}'] || "
				. ($this->showPercentage ? "y+'%'" : "y;")
				. "
				var formatterTemplate = `$formatterTemplate`;
				var formatterHtml = formatterTemplate.replace('{formatValue}', formatValue);
				return formatterHtml;
			}";
		}
		else
		{
			$prefix = Util::get($columns[$valueKey],"prefix","");
			$suffix = Util::get($columns[$valueKey],"suffix","");
			$options["formatter"]="function(y,data){return '$prefix'+y+'$suffix'}";
		}
		return array_merge($options,$this->options);
    } 
}