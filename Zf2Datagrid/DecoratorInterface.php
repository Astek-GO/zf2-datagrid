<?php

namespace Zf2Datagrid;

/**
 * Interface DecoratorInterface
 *
 * @package Zf2Datagrid
 */
interface DecoratorInterface
{
    /**
     * @param $data
     *
     * @return mixed
     */
    public function render($data);
}
