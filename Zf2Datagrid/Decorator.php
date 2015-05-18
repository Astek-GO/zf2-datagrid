<?php

namespace Zf2Datagrid;

/**
 * Interface Decorator
 *
 * @package Zf2Datagrid
 */
interface Decorator
{
    /**
     * @param $data
     *
     * @return mixed
     */
    public function render($data);
}