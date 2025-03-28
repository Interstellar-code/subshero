<?php
/**
 * This file contains Morris chart widget
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license
 */
namespace koolreport\morris_chart;
use \koolreport\core\Utility as Util;

class Morris extends \koolreport\core\Widget
{
    protected $chartId;
    protected $type;
    protected $options;
    protected $width;
    protected $height;
    protected $title;
    protected $colorScheme;

    public function version()
    {
        return "3.0.0-dev";
    }
    protected function resourceSettings()
    {
        return array(
            "library"=>array("raphael"),
            "folder"=>"assets",
            "js"=>array(
                "morris.min.js",
            ),
            "css"=>array(
                "morris.css",
            )
        );        
    }

    protected function onInit()
    {

        $this->useDataSource();
        $this->chartId = "morris_".Util::getUniqueId();
        $this->columns = Util::get($this->params,"columns",null);
        $this->options = Util::get($this->params,"options",array());
        $this->width = Util::get($this->params,"width","100%");
        $this->height = Util::get($this->params,"height","400px");
        $this->title = Util::get($this->params,"title");
        $this->type = Util::getClassName($this);

        if(!$this->dataStore)
        {
            //Backward compatible with setting through "data"
			$data = Util::get($this->params,"data");
			if(is_array($data))
			{
				if(count($data)>0)
				{
					$this->dataStore = new DataStore;
					$this->dataStore->data($data);
					$row = $data[0];
					$meta = array("columns"=>array());
					foreach($row as $cKey=>$cValue)
					{
						$meta["columns"][$cKey] = array(
							"type"=>Util::guessType($cValue),
						);
					}
					$this->dataStore->meta($meta);	
				}
				else
				{
					$this->dataStore = new DataStore;
					$this->dataStore->data(array());
					$this->dataStore->meta(array("columns"=>array()));
				}	
			}
			if($this->dataStore==null)
			{
				throw new \Exception("[dataSource] is required");
				return;
			}
        }



        //Color Scheme
        $this->colorScheme = Util::get($this->params,"colorScheme");
        if(!is_array($this->colorScheme))
        {
            $theme = $this->getReport()->getTheme(); 
            if($theme)
            {
                $theme->applyColorScheme($this->colorScheme);
            }
        }
        if(!is_array($this->colorScheme))
        {
            $this->colorScheme = null;
        }



        
        if($this->type=="Morris")
        {
            Util::get($this->params,"type");
        }
    }

    protected function getColumnList()
    {
        $meta = $this->dataStore->meta();
        $columns=array();
        if($this->columns!=null)
        {
            foreach($this->columns as $cKey=>$cValue)
            {
                if(gettype($cValue)=="array")
                {
                    $columns[$cKey] = array_merge($meta["columns"][$cKey],$cValue);
                }
                else
                {
                    $columns[$cValue] = $meta["columns"][$cValue];
                }
            }
        }
        else
        {
            $this->dataStore->popStart();
            $row = $this->dataStore->pop();
            $keys = array_keys($row);
            foreach($keys as $ckey)
            {
                $columns[$ckey] = $meta["columns"][$ckey];
            }
        }
        return $columns;
    }

    protected function encode($json)
    {
        $str = json_encode($json);

        foreach($json as $key=>$value)
        {
            if(gettype($value)==="string" && strpos($value,"function")===0)
            {
                $str = str_replace("\"$key\":\"$value\"","\"$key\":$value",$str);
            }
        }
        return $str;
    }

    protected function roundNumber($value,$decimals)
    {
       return round($value*pow(10,$decimals))/pow(10,$decimals);
    }


    protected function prepareOptions()
    {
        return $this->options;
    }

    protected function buildHoverCallback($options, $columns)
    {
        $hasFormatValue = Util::get($options, "hasFormatValue");
        $hasHoverTitle = Util::get($this->params, "hoverTitleTemplate");
        $hasHoverItem = Util::get($this->params, "hoverItemTemplate");
        
        if (!$hasFormatValue && !$hasHoverTitle && !$hasHoverItem) return $options;

        $hoverTitleTemplate = Util::get($this->params, "hoverTitleTemplate",
            "<div class=\'morris-hover-row-label\'>{titleValue}</div>"
        );
        $hoverItemTemplate = Util::get($this->params, "hoverItemTemplate",
            "<div class=\'morris-hover-point\' style=\'color: {itemColor}\'>
                {itemName}:
                {itemValue}
            </div>"
        );
        $defaultBarColors = ['#0b62a4', '#7a92a3', '#4da74d', '#afd8f8', '#edc240', '#cb4b4b', '#9440ed'];
        $barColors = !empty($this->colorScheme) ? $this->colorScheme : $defaultBarColors;
        $barColorsJson = json_encode($barColors);
        $columnNames = array_keys($columns);
        $columnNamesJson = json_encode($columnNames);
        $options["hoverCallback"] = "function(index, options, content, row) {
            // console.log(index, row, content);
            var barColors = $barColorsJson;
            var columnNames = $columnNamesJson;
            var hoverHtml = '';
            var hoverTitleTemplate = `$hoverTitleTemplate`;
            var hoverItemTemplate = `$hoverItemTemplate`;
            var hoverTitle = hoverTitleTemplate.replace('{titleValue}', row[columnNames[0]]);
            hoverHtml += hoverTitle;
            for (var i=1; i<columnNames.length; i+=1) {
                var columnName = columnNames[i];
                var columnFormattedValue = row['{' + columnName + '_formatValue}'];
                if (typeof columnFormattedValue === 'undefined') columnFormattedValue = row[columnName];
                // console.log(columnName, columnFormattedValue);
                var hoverItem = hoverItemTemplate;
                hoverItem = hoverItem.replace('{itemColor}', barColors[i - 1]);
                hoverItem = hoverItem.replace('{itemName}', columnName);
                hoverItem = hoverItem.replace('{itemValue}', columnFormattedValue);
                hoverHtml += hoverItem;
            }
            return hoverHtml;
        }";
        
        return $options;
    }

    protected function onRender()
    {
        $this->template('Morris',array(
            "options"=>$this->prepareOptions(),
        ));
    }
}