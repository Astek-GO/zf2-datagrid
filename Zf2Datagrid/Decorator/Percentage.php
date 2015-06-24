<?php

namespace Zf2Datagrid\Decorator;

use PHPExcel\Style_NumberFormat;
use Zf2Datagrid\Decorator;

/**
 * Class Percentage
 *
 * @package Zf2Datagrid\Decorator
 */
class Percentage extends Numeric
{
    /**
     * @param int    $precision
     * @param string $decimalSeparator
     * @param string $thousandsSeparator
     */
    public function __construct($precision = 2, $decimalSeparator = ',', $thousandsSeparator = ' ')
    {
        parent::__construct($precision, $decimalSeparator, $thousandsSeparator, '%');
    }

    /**
     * @return string
     */
    public function getExcelFormat()
    {
        return Style_NumberFormat::FORMAT_PERCENTAGE_00;
    }
}