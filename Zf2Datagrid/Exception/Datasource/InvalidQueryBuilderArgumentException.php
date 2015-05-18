<?php

namespace Zf2Datagrid\Exception\Datasource;

use Doctrine\ORM\QueryBuilder;
use Exception;

/**
 * Class InvalidArgumentException
 *
 * @package Zf2Datagrid\Exception\Datasource
 */
class InvalidQueryBuilderArgumentException extends Exception
{
    public function __construct()
    {
        $message = sprintf('You should provide an instance of %s.', QueryBuilder::class);

        parent::__construct($message);
    }
}