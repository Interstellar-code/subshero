<?php

namespace koolreport\excel;

use \koolreport\core\Utility as Util;
use \PhpOffice\PhpSpreadsheet as ps;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class PivotMatrixBuilder extends PivotTableBuilder
{
    protected $template = "pivotmatrix";
}