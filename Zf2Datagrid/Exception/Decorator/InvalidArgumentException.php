<?php

namespace Zf2Datagrid\Exception\Decorator;

use Exception;

/**
 * Class InvalidArgumentException
 *
 * @package Zf2Datagrid\Exception\Decorator
 */
class InvalidArgumentException extends Exception
{
    public function __construct()
    {
        parent::__construct('Decorator must implements Zf2Datagrid\Decorator.');
    }
}