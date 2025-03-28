<?php
/**
 * This file contains widget wrapper class for date time picker
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\inputs;
use \koolreport\core\Utility;

class DateTimePicker extends InputControl
{
    protected $locale;
    protected $format;
    protected $icon;
    protected $disableDates;
    protected $minDate;
    protected $maxDate;
    protected $options;
    protected $disabled;

    protected function resourceSettings()
    {
        switch($this->getThemeBase())
        {
            case "bs4":
                return array(
                    "library"=>array("jQuery"),
                    "folder"=>"bower_components",
                    "js"=>array(
                        "moment/moment.min.js",
                        "moment/locales.min.js",
                        array(                    
                            "datetimepicker/js/datetimepicker.bs4.min.js",
                            "datetimepicker/js/linkedpicker.js",
                        ),
                    ),
                    "css"=>array(
                        "datetimepicker/css/datetimepicker.bs4.min.css",
                    )
                );    
            case "bs3":
            default:
                return array(
                    "library"=>array("jQuery"),
                    "folder"=>"bower_components",
                    "js"=>array(
                        "moment/moment.min.js",
                        "moment/locales.min.js",
                        array(                    
                            "datetimepicker/js/datetimepicker.min.js",
                            "datetimepicker/js/linkedpicker.js",
                        ),
                    ),
                    "css"=>array(
                        "datetimepicker/css/datetimepicker.min.css",
                    )
                );            
        }
    }

    protected function onInit()
    {
        parent::onInit();
        $this->format = Utility::get($this->params,"format","MMM Do, YYYY");
        $this->icon = Utility::get($this->params,"icon","glyphicon glyphicon-calendar fa fa-calendar");
        $this->disabledDates = Utility::get($this->params,"disabledDates");
        $this->minDate = Utility::get($this->params,"minDate");
        $this->maxDate = Utility::get($this->params,"maxDate");
        $this->disabled = Utility::get($this->params,"disabled",false);
        if($this->value===null)
        {
            $this->value=date('Y-m-d H:i:s');
        }
        else
        {
            $date = new \DateTime($this->value);
            $this->value = $date->format("Y-m-d H:i:s");
        }
        $this->locale = Utility::get($this->params,"locale");
        $this->options = Utility::get($this->params,"options");
    }

    protected function onRender()
    {
        $settings = array(
            "icons"=>array(
                "time"=>"fa far fa-clock",
            )
        );
        if($this->options!==null)
        {
            $settings = Utility::arrayMergeRecursive($settings,$this->options);
        }

        $settings["format"] = $this->format;
        $settings["defaultDate"] = $this->value;
        if($this->disabledDates)
        {
            $settings["disabledDates"] = $this->disabledDates;
        }
        if($this->minDate && strpos($this->minDate,"@")===false)
        {
            $settings["minDate"] = $this->minDate;
        }

        if($this->maxDate && strpos($this->maxDate,"@")===false)
        {
            $settings["maxDate"] = $this->maxDate;
        }
        if($this->locale)
        {
            $settings["locale"] = $this->locale;
        }

        $this->template('DateTimePicker',array(
            'settings'=>$settings,
        ));
    }

    /**
     * Convert date range from php date format to momment js format
     * @param mixed $phpDateFormat 
     * @return mixed 
     */
    static function toMomentFormat($phpDateFormat) 
    {
        $map = [
            "jS"=>"Do",
            "d"=>"DD",
            "D"=>"ddd",
            "j"=>"D",
            "l"=>"dddd",
            "w"=>"d",
            "z"=>"DDD",
            "W"=>"W",
            "F"=>"MMMM",
            "m"=>"MM",
            "M"=>"MMM",
            "n"=>"M",
            "Y"=>"YYYY",
            "y"=>"YY",
            "a"=>"a",
            "A"=>"A",
            "g"=>"h",
            "G"=>"k",
            "h"=>"hh",
            "H"=>"HH",
            "i"=>"mm",
            "s"=>"ss",
        ];
    
        $momentFormat = $phpDateFormat;
        $replaces = [];
    
        foreach($map as $k=>$v) {
            if(strpos($momentFormat,$k)!==false) {
                array_push($replaces,$v);
                $i = count($replaces)-1;
                $momentFormat = str_replace($k,"{".$i."}",$momentFormat);
            }
        }
        foreach($replaces as $i=>$v) {
            $momentFormat = str_replace("{".$i."}",$v,$momentFormat);
        }
        return $momentFormat;
    }    
}