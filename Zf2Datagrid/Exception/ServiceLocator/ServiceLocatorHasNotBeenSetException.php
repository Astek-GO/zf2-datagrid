<?php

namespace Zf2Datagrid\Exception\Table;

use Exception;

/**
 * Class ServiceLocatorHasNotBeenSetException
 *
 * @package Zf2Datagrid\Exception\Table
 */
class ServiceLocatorHasNotBeenSetException extends Exception
{
    public function __construct()
    {
        parent::__construct('Service locator has not been set.');
    }
}