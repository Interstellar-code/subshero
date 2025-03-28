<?php
/**
 * This file contains wrapper class for Textbox
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\inputs;
use \koolreport\core\Utility;

class TextBox extends InputControl
{
    protected $frontText;
    protected $backText;

    protected function onInit()
    {
        parent::onInit();
        $this->frontText = Utility::get($this->params,"frontText");
        $this->backText = Utility::get($this->params,"backText");
    }

    protected function resourceSettings()
    {
        return array(
            "library"=>array("jQuery"),
        );
    }
}