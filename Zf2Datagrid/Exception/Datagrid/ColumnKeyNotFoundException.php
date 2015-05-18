<?php

namespace Zf2Datagrid\Exception\Datagrid;

use Exception;

/**
 * Class ColumnKeyNotFoundException
 *
 * @package Zf2Datagrid\Exception\Datagrid
 */
class ColumnKeyNotFoundException extends Exception
{
    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $message = sprintf('A column with [key=%s] has already been added.', $key);

        parent::__construct($message);
    }
}
