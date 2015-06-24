<?php

namespace Zf2Datagrid\Decorator;

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
}