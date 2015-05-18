<?php

namespace Zf2Datagrid\Exception\Datagrid;

use Zf2Datagrid\Column;
use Exception;

/**
 * Class ColumnAlreadyAdded
 *
 * @package Zf2Datagrid\Exception\Datagrid
 */
class ColumnAlreadyAdded extends Exception
{
    /**
     * @param Column $column
     */
    public function __construct(Column $column)
    {
        $message = sprintf('A column with [key=%s] has already been added.', $column->getKey());

        parent::__construct($message);
    }
}