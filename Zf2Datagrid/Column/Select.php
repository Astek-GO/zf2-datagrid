<?php

namespace Zf2Datagrid\Column;

use Zf2Datagrid\Column;

/**
 * Class Select
 *
 * @package Zf2Datagrid\Column
 */
class Select extends Column
{
    /**
     * @var string
     */
    protected $alias;

    /**
     * @param string $column
     * @param        $alias
     */
    public function __construct($column, $alias)
    {
        parent::__construct($column);

        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getSortColumn()
    {
        return $this->getAlias() . '.' . $this->getKey();
    }
}