<?php

namespace koolreport\chartjs;
use \koolreport\core\Utility;

class ComboChart extends BarChart
{
    protected $type="bar";
    protected $chartSettings = [
        "chartType", "axis",
        "backgroundColor", "borderColor", "borderSkipped", "borderWidth", "data", "hoverBackgroundColor", 
        "hoverBorderColor", "hoverBorderWidth", "label", "order", "xAxisID", "yAxisID",
        "backgroundColor", "borderCapStyle", "borderColor", "borderDash", "borderDashOffset",
        "borderJoinStyle", "borderWidth", "cubicInterpolationMode", "clip", "fill", "hoverBackgroundColor",
        "hoverBorderCapStyle", "hoverBorderColor", "hoverBorderDash", "hoverBorderDashOffset",
        "hoverBorderJoinStyle", "hoverBorderWidth", "label", "lineTension", "order", "pointBackgroundColor",
        "pointBorderColor", "pointBorderWidth", "pointHitRadius", "pointHoverBackgroundColor",
        "pointHoverBorderColor", "pointHoverBorderWidth", "pointHoverRadius", "pointRadius", "pointRotation",
        "pointStyle", "showLine", "spanGaps", "steppedLine", "xAxisID", "yAxisID"
    ];
}