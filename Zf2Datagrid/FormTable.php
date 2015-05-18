<?php

namespace Zf2Datagrid;

class FormTable
{
    /**
     * @var Table
     */
    private $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }
}