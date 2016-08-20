<?php

namespace Zf2Datagrid\Decorator;

use Zf2Datagrid\DecoratorInterface;

/**
 * Class Number
 *
 * @package Zf2Datagrid\Decorator
 */
class Number implements DecoratorInterface
{
    /**
     * @var int
     */
    protected $precision;

    /**
     * @var string
     */
    protected $decimalSeparator;

    /**
     * @var string
     */
    protected $thousandsSeparator;

    /**
     * @var string
     */
    protected $append;

    /**
     * @param int    $precision
     * @param string $decimalSeparator
     * @param string $thousandsSeparator
     * @param string $append
     */
    public function __construct($precision = 2, $decimalSeparator = ',', $thousandsSeparator = ' ', $append = null)
    {
        $this->precision          = $precision;
        $this->decimalSeparator   = $decimalSeparator;
        $this->thousandsSeparator = $thousandsSeparator;
        $this->append             = $append;
    }

    /**
     * @param $append
     *
     * @return $this
     */
    public function setAppend($append)
    {
        $this->append = $append;

        return $this;
    }

    /**
     * @param $data
     *
     * @return float
     */
    public function render($data)
    {
        $data = number_format(
            doubleval($data),
            $this->precision,
            $this->decimalSeparator,
            $this->thousandsSeparator
        );

        if (null !== $this->append) {
            $data = vsprintf('%s %s', [$data, $this->append]);
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getExcelFormat()
    {
        return vsprintf('%s%s%s', [
            str_repeat(
                vsprintf('%s%s', [
                    '###',
                    $this->thousandsSeparator,
                ]),
                6
            ),
            '##0.00',
            (null !== $this->append ? $this->append : '')
        ]);
    }
}
