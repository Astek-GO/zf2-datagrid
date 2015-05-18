<?php

namespace Zf2Datagrid\Decorator;

use Zf2Datagrid\Decorator;

/**
 * Class NoValue
 *
 * @package Zf2Datagrid\Decorator
 */
class NoValue implements Decorator
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