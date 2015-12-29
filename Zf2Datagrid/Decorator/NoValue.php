<?php

namespace Zf2Datagrid\Decorator;

use Zf2Datagrid\DecoratorInterface;

/**
 * Class NoValue
 *
 * @package Zf2Datagrid\Decorator
 */
class NoValue implements DecoratorInterface
{
    /**
     * @param $data
     *
     * @return mixed
     */
    public function render($data)
    {
        return '';
    }
}
