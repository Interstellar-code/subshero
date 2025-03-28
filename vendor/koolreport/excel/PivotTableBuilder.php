<?php

namespace koolreport\excel;

use \koolreport\core\Utility as Util;
use \PhpOffice\PhpSpreadsheet as ps;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class PivotTableBuilder extends WidgetBuilder
{
    protected $template = "pivottable";

    public function saveContentToSheet($content, $sheet)
    {
        $this->sheet = $sheet;

        list($highestRow, $highestColumn, $range) =
            $this->getSheetRange($sheet, $content);
        $option = $content;
        $pos = Coordinate::coordinateFromString($range[1]);
        $option['startColumn'] = Coordinate::columnIndexFromString($pos[0]);
        $option['startRow'] = $pos[1];

        $this->option = $option;
        $this->saveDataStoreToSheet();
    }

    protected function buildDatastore()
    {
        $option = $this->option;
        $ds = Util::get($option, 'dataSource', new \koolreport\core\DataStore());
        $filtering = Util::get($option, 'filtering', null);
        if (!empty($filtering)) {
            $ds = $ds->filter($filtering);
        }
        $sorting = Util::get($option, 'sorting', null);
        if (!empty($sorting)) {
            $ds = $ds->sort($sorting);
        }
        $paging = Util::get($option, 'paging', null);
        if (!empty($paging)) {
            $ds = $ds->paging($paging[0], $paging[1]);
        }
        return $ds;
    }

    public function saveDataStoreToSheet()
    {
        $ds = $this->ds = $this->buildDatastore();
        $sheet = $this->sheet;
        $option = $this->option;

        $totalName = Util::get($option, 'totalName', 'Total');
        $emptyValue = Util::get($option, 'emptyValue', '-');
        $hideSubTotalRows = Util::get($option, 'hideSubTotalRows', false);
        $hideSubTotalColumns = Util::get($option, 'hideSubTotalColumns', false);
        $hideTotalRow = Util::get($option, 'hideTotalRow', false);
        $hideGrandTotalRow = Util::get($option, 'hideGrandTotalRow', $hideTotalRow);
        $hideTotalColumn = Util::get($option, 'hideTotalColumn', false);
        $hideGrandTotalColumn = Util::get($option, 'hideGrandTotalColumn', $hideTotalColumn);
        $showDataHeaders = Util::get($option, 'showDataHeaders', false);
        $excelStyle = Util::get($option, 'excelStyle', []);
        $colMetas = $ds->meta()['columns'];
        // echo "colMetas = "; print_r($colMetas); echo " <br> ";

        $pivotUtil = new \koolreport\pivot\PivotUtil($ds, $option);
        $fni = $pivotUtil->getFieldsNodesIndexes();
        // Util::prettyPrint($fni);
        // exit;
        $rowNodes = $fni['mappedRowNodes'];
        $colNodes = $fni['mappedColNodes'];
        $rowIndexes = $fni['rowIndexes'];
        $colIndexes = $fni['colIndexes'];
        $rowNodesInfo = $fni['rowNodesInfo'];
        $colNodesInfo = $fni['colNodesInfo'];
        $colFields = array_values($fni['colFields']);
        $rowFields = array_values($fni['rowFields']);
        $dataFields = array_values($fni['dataFields']);
        $mappedDataFields = $fni['mappedDataFields'];
        $mappedColFields = $fni['mappedColFields'];
        $mappedRowFields = $fni['mappedRowFields'];
        $mappedDataHeaders = $fni['mappedDataHeaders'];
        $indexToMappedData = $fni['indexToMappedData'];
        $rowNodesExcelStyle = $fni['rowNodesExcelStyle'];
        $colNodesExcelStyle = $fni['colNodesExcelStyle'];
        $dataHeadersExcelStyle = $fni['dataHeadersExcelStyle'];
        // print_r($colNodesExcelStyle); exit;
        $indexToDataExcelStyle = $fni['indexToDataExcelStyle'];
        // print_r($indexToDataExcelStyle); exit;

        $startCol = Util::get($option, 'startColumn', 1);
        $startRow = Util::get($option, 'startRow', 1);

        $template = $this->template;
        if ($template === "pivottable") {
            // Create data fields zone
            $cell = Coordinate::stringFromColumnIndex($startCol) . ($startRow);
            $colspan = count($rowFields) - 1;
            $rowspan = count($colFields) - 1 + ($showDataHeaders ? 1 : 0);
            $endCell = Coordinate::stringFromColumnIndex(
                $startCol + $colspan
            ) . ($startRow + $rowspan);
            $dataZoneValue = implode(' | ', $mappedDataFields);
            $sheet->setCellValue($cell, $dataZoneValue);
            $sheet->mergeCells($cell . ":" . $endCell);
            $style = $sheet->getStyle($cell);
            $fieldStyle = Util::get($excelStyle, 'dataField', []);
            $fieldStyle = Util::map($fieldStyle, [$dataFields], []);
            $style->applyFromArray($fieldStyle);
            $style->getAlignment()->setHorizontal(
                ps\Style\Alignment::HORIZONTAL_CENTER
            );
            $style->getAlignment()->setVertical(
                ps\Style\Alignment::VERTICAL_CENTER
            );
        } else if ($template === "pivotmatrix") {
            $cell = Coordinate::stringFromColumnIndex($startCol) . ($startRow);
            $colspan = count($rowFields) - 1;
            $rowspan = count($colFields) - 1;
            $endCell = Coordinate::stringFromColumnIndex(
                $startCol + $colspan) . ($startRow + $rowspan);
            $dataZoneValue = implode(' | ', $mappedDataFields);
            $sheet->setCellValue($cell, $dataZoneValue);
            $sheet->mergeCells($cell . ":" . $endCell);
            $style = $sheet->getStyle($cell);
            $fieldStyle = Util::get($excelStyle, 'dataField', []);
            $fieldStyle = Util::map($fieldStyle, [$dataFields], []);
            $style->applyFromArray($fieldStyle);
            $style->getAlignment()->setHorizontal(
                ps\Style\Alignment::HORIZONTAL_CENTER);
            $style->getAlignment()->setVertical(
                ps\Style\Alignment::VERTICAL_CENTER);
        }

        // print_r($colIndexes); exit;
        $showColData = [];
        foreach ($colFields as $i => $f) {
            foreach ($colIndexes as $c => $j) {
                $nodeMark = $colNodesInfo[$j];
                $showColHeader = isset($nodeMark[$f]['numChildren']);
                $isTotal = isset($nodeMark[$f]['total']);
                $isSubTotal = $isTotal && $i > 0;
                $isGrandTotal = $isTotal && $i === 0;
                if (!isset($showColData[$c])) $showColData[$c] = true;
                if ($showColHeader && $hideSubTotalColumns && $isSubTotal)
                    $showColData[$c] = false;
                if ($showColHeader && $hideGrandTotalColumn && $isGrandTotal)
                    $showColData[$c] = false;
            }
        }
        // print_r($showColData); exit;

        if ($template === "pivotmatrix") {
            //Create column fields zone
            $c = count($colIndexes);
            $numSlippedColumns = 0;
            for ($n = 0; $n < $c; $n++)
                if (!$showColData[$n])
                    $numSlippedColumns += 1;
            $totalColNodeSpan = ($c - $numSlippedColumns) * count($dataFields);
            // echo "totalColNodeSpan=$totalColNodeSpan"; exit;
            foreach ($colFields as $i => $f) {
                $cell = Coordinate::stringFromColumnIndex($startCol + count($rowFields)) . ($startRow + $i);
                $colspan = $totalColNodeSpan - 1;
                $rowspan = 0;
                $endCell = Coordinate::stringFromColumnIndex(
                    $startCol + count($rowFields) + $colspan) . ($startRow + $i + $rowspan);
                $sheet->setCellValue($cell, $mappedColFields[$f]);
                $sheet->mergeCells($cell . ":" . $endCell);

                $style = $sheet->getStyle($cell);
                $fieldStyle = Util::get($excelStyle, 'columnField', []);
                $fieldStyle = Util::map($fieldStyle, [$colFields], []);
                $style->applyFromArray($fieldStyle);
                $style->getAlignment()->setHorizontal(
                    ps\Style\Alignment::HORIZONTAL_CENTER);
                $style->getAlignment()->setVertical(
                    ps\Style\Alignment::VERTICAL_CENTER);
            }

            $startRow = $startRow + count($colFields);

            //Create row fields zone
            foreach ($rowFields as $i => $f) {
                $cell = Coordinate::stringFromColumnIndex($startCol + $i) . ($startRow);
                $colspan = 0;
                $rowspan = count($colFields) - 1 + ($showDataHeaders ? 1 : 0);
                $endCell = Coordinate::stringFromColumnIndex(
                    $startCol + $i + $colspan
                ) . ($startRow + $rowspan);
                $sheet->setCellValue($cell, $mappedRowFields[$f]);
                $sheet->mergeCells($cell . ":" . $endCell);

                $style = $sheet->getStyle($cell);
                $fieldStyle = Util::get($excelStyle, 'rowField', []);
                $fieldStyle = Util::map($fieldStyle, [$rowFields], []);
                $style->applyFromArray($fieldStyle);
                $style->getAlignment()->setHorizontal(
                    ps\Style\Alignment::HORIZONTAL_CENTER
                );
                $style->getAlignment()->setVertical(
                    ps\Style\Alignment::VERTICAL_CENTER
                );
            }
        }

        // Create column headers zone
        foreach ($colFields as $i => $f) {
            foreach ($colIndexes as $c => $j) {
                $node = $colNodes[$j];
                $nodeMark = $colNodesInfo[$j];
                $nodeExcelStyle = $colNodesExcelStyle[$j];
                // print_r($nodeExcelStyle); exit;
                $showColHeader = isset($nodeMark[$f]['numChildren']);
                if ($showColHeader && $showColData[$c]) {
                    $isTotal = isset($nodeMark[$f]['total']);
                    $numSlippedColumns = 0;
                    for ($n = 0; $n < $c; $n++)
                        if (!$showColData[$n])
                            $numSlippedColumns += count($dataFields);
                    $row = $startRow + $i;
                    $col = $startCol + count($rowFields)
                        + $c * count($dataFields) - $numSlippedColumns;
                    $rowspan = $isTotal ? $nodeMark[$f]['level'] : 1;
                    $colspan = $hideSubTotalColumns ?
                        $nodeMark[$f]['numLeaf'] : $nodeMark[$f]['numChildren'];
                    $endRow = $row + $rowspan - 1;
                    $endCol = $col + $colspan - 1;
                    $cell = Coordinate::stringFromColumnIndex($col) . $row;
                    $endCell = Coordinate::stringFromColumnIndex($endCol) . $endRow;
                    $sheet->mergeCells($cell . ":" . $endCell);

                    $value = $node[$f];
                    $sheet->getCell($cell)->setValue($value);
                    $style = $sheet->getStyle($cell);
                    $style->getAlignment()->setHorizontal(
                        ps\Style\Alignment::HORIZONTAL_CENTER
                    );
                    $style->getAlignment()->setVertical(
                        ps\Style\Alignment::VERTICAL_CENTER
                    );
                    $excelStyle = Util::get($nodeExcelStyle, $f, []);
                    // print_r($excelStyle); exit;
                    $style->applyFromArray($excelStyle);
                }
            }
        }

        // Create data headers zone
        if ($showDataHeaders) {
            $row = $startRow + count($colFields);
            $col = $startCol + count($rowFields);
            foreach ($colIndexes as $c => $j) {
                if (!$showColData[$c]) continue;
                foreach ($dataFields as $di => $df) {
                    $cell = Coordinate::stringFromColumnIndex($col) . $row;
                    $mappedDH = $mappedDataHeaders[$df];
                    $sheet->getCell($cell)->setValue($mappedDH);
                    $style = $sheet->getStyle($cell);
                    $excelStyle = Util::get($dataHeadersExcelStyle, $df, []);
                    $style->applyFromArray($excelStyle);
                    $col++;
                }
            }
            $startRow++;
        }

        // Create row headers zone
        $maxLength = array_fill(0, count($rowFields), 0);
        $numSkippedRows = 0;
        foreach ($rowIndexes as $r => $i) {
            $node = $rowNodes[$i];
            $nodeMark = $rowNodesInfo[$i];
            $nodeExcelStyle = $rowNodesExcelStyle[$i];
            $showRowData = true;
            foreach ($rowFields as $j => $f) {
                $showRowHeader = isset($nodeMark[$f]['numChildren']);
                $isTotal = isset($nodeMark[$f]['total']);
                $isSubTotal = $isTotal && $j > 0;
                $isGrandTotal = $isTotal && $j === 0;
                if ($showRowHeader && $hideSubTotalRows && $isSubTotal)
                    $showRowData = false;
                if ($showRowHeader && $hideGrandTotalRow && $isGrandTotal)
                    $showRowData = false;
                if ($showRowHeader && !$showRowData) $numSkippedRows++;
                if ($showRowHeader && $showRowData) {
                    $row = $startRow + count($colFields) + $r - $numSkippedRows;
                    $col = $startCol + $j;
                    $rowspan = $hideSubTotalRows ?
                        $nodeMark[$f]['numLeaf'] : $nodeMark[$f]['numChildren'];
                    $colspan = $isTotal ? $nodeMark[$f]['level'] : 1;
                    $endRow = $row + $rowspan - 1;
                    $endCol = $col + $colspan - 1;
                    $cell = Coordinate::stringFromColumnIndex($col) . $row;
                    $endCell = Coordinate::stringFromColumnIndex($endCol) . $endRow;
                    $sheet->mergeCells($cell . ":" . $endCell);

                    $value = $node[$f];
                    $sheet->getCell($cell)->setValue($value);
                    $style = $sheet->getStyle($cell);
                    $style->getAlignment()->setVertical(
                        ps\Style\Alignment::VERTICAL_CENTER
                    );
                    $excelStyle = Util::get($nodeExcelStyle, $f, []);
                    $style->applyFromArray($excelStyle);
                    if ($maxLength[$j] < strlen($value)) {
                        $maxLength[$j] = strlen($value);
                    }
                }
            }

            if (!$showRowData) continue;

            // Create data cells zone
            foreach ($colIndexes as $c => $j) {
                if (!$showColData[$c]) continue;

                $numSlippedColumns = 0;
                for ($n = 0; $n < $c; $n++)
                    if (!$showColData[$n])
                        $numSlippedColumns += count($dataFields);
                $mappedDataRow = Util::get($indexToMappedData, [$i, $j], []);
                $dataRowExcelStyle = Util::get($indexToDataExcelStyle, [$i, $j], []);
                // print_r($indexToDataExcelStyle[$i][$j]); exit;
                // echo "$i - $j "; print_r($dataRowExcelStyle); exit;
                foreach ($dataFields as $k => $df) {
                    $col = $startCol + count($rowFields) + $c * count($dataFields)
                        + $k - $numSlippedColumns;
                    $col = Coordinate::stringFromColumnIndex($col);
                    $row = $startRow + count($colFields) + $r - $numSkippedRows;
                    $cell = $col . $row;
                    if (isset($mappedDataRow[$df])) {
                        $value = $mappedDataRow[$df];
                        $colMeta = Util::get($colMetas, $df, []);
                        $type = Util::get($colMeta, 'type', 'string');
                        $format = $colMeta;
                        $formatCode = "";
                        switch ($type) {
                            case "number":
                                $decimals = Util::get($format, "decimals", 0);
                                $prefix = Util::get($format, "prefix", "");
                                $suffix = Util::get($format, "suffix", "");
                                $zeros = "";
                                for ($deIndex = 0; $deIndex < $decimals; $deIndex++) $zeros .= "0";
                                if ($decimals > 0) $zeros = ".$zeros";
                                $formatCode = "\"{$prefix}\"#,##0{$zeros}\"{$suffix}\"";
                                $formatCode = Util::get($format, "excelFormatCode", $formatCode);
                                break;
                            default:
                                $value = Util::format($value, $format);
                                break;
                        }
                    } else {
                        $value = $emptyValue;
                    }
                    $sheet->getCell($cell)->setValue($value);
                    $style = $sheet->getStyle($cell);
                    if (!empty($formatCode)) {
                        $style->getNumberFormat()->setFormatCode($formatCode);
                    }
                    $style->getAlignment()->setHorizontal(
                        ps\Style\Alignment::HORIZONTAL_RIGHT
                    );
                    $excelStyle = Util::get($dataRowExcelStyle, $df, []);
                    $style->applyFromArray($excelStyle);
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        }

        for ($i = 0; $i < sizeof($maxLength); $i++) {
            $col = Coordinate::stringFromColumnIndex($startCol + $i);
            // $sheet->getColumnDimension($col)->setWidth($maxLength[$i]);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
