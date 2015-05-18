<?php

namespace Zf2Datagrid\Exception\Table;

use Exception;

/**
 * Class NoRendererHasBeenSetException
 *
 * @package Zf2Datagrid\Exception\Table
 */
class NoRendererHasBeenSetException extends Exception
{
    public function __construct()
    {
        parent::__construct('No renderer has been set.');
    }
}