<?php

namespace Zf2Datagrid\Decorator;

use PHPExcel\Style_NumberFormat;
use Zf2Datagrid\Decorator;

/**
 * Class Integer
 *
 * @package Zf2Datagrid\Decorator
 */
class Integer extends Numeric
{
    public function __construct()
    {
        parent::__construct(0);
    }

    /**
     * @return string
     */
    public function getExcelFormat()
    {
        return Style_NumberFormat::FORMAT_NUMBER;
    }
}
