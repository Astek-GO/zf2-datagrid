<?php

namespace Zf2Datagrid\Decorator;

use Zf2Datagrid\Decorator;

/**
 * Class Numeric
 *
 * @package Zf2Datagrid\Decorator
 */
class Numeric implements Decorator
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
        // TODO : évaluer le nombre de # à l'avance pour éviter un décalage du signe (-)

        return vsprintf('%s%s%s', [
            str_repeat(
                vsprintf('%s%s', [
                    '###',
                    $this->thousandsSeparator,
                ]),
                6
            ),
            '##0.00',
            (null != $this->append ? $this->append : '')
        ]);
    }
}