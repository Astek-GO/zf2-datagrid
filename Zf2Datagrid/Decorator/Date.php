<?php

namespace Zf2Datagrid\Decorator;

use Zf2Datagrid\DecoratorInterface;
use DateTime;

/**
 * Class Date
 *
 * @package Zf2Datagrid\Decorator
 */
class Date implements DecoratorInterface
{
    /**
     * @var string
     */
    protected $format;

    /**
     * @param string $format
     */
    public function __construct($format = 'd/m/Y')
    {
        $this->format = $format;
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function render($data)
    {
        if (null === $data) {
            return null;
	}

        if (! $data instanceof DateTime) {
            $data = new DateTime($data);
        }

        return $data->format($this->format);
    }
}
