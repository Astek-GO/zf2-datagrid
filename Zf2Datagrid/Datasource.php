<?php

namespace Zf2Datagrid;

use OutOfBoundsException;
use Zend\Paginator\Adapter\AdapterInterface;

/**
 * Class Datasource
 *
 * @package Zf2Datagrid
 */
abstract class Datasource
{
    /**
     * @var Column[]
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $sort = [];

    /**
     * @var AdapterInterface
     */
    protected $paginatorAdapter;

    /**
     * @var int
     */
    protected $first;

    /**
     * @var int
     */
    protected $max;

    /**
     * @var int
     */
    protected $resultCount = 0;

    /**
     * @param $builder
     */
    abstract public function __construct($builder);

    /**
     * @param Column[] $columns
     *
     * @return $this
     */
    public function setColumns(array $columns = [])
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param string $colKey
     * @param string $sortDirection
     *
     * @return $this
     */
    public function addSortCondition($colKey, $sortDirection = 'ASC')
    {
        /** @var Column $column */
        foreach ($this->columns as $column) {
            if (isset($colKey) == $column->getKey()) {
                $this->sort[$colKey] = $sortDirection;
            }
        }

        return $this;
    }

    /**
     * @param array $sortConditions
     *
     * @return $this
     */
    public function setSortConditions(array $sortConditions = [])
    {
        /** @var string $column */
        /** @var string $sortDir */

        foreach ($sortConditions as $column => $sortDir) {
            $this->addSortCondition($column, $sortDir);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getSortConditions()
    {
        return $this->sort;
    }

    /**
     * @param AdapterInterface $paginator
     *
     * @return $this
     */
    public function setPaginatorAdapter(AdapterInterface $paginator)
    {
        $this->paginatorAdapter = $paginator;

        return $this;
    }

    /**
     * @return AdapterInterface
     */
    public function getPaginatorAdapter()
    {
        return $this->paginatorAdapter;
    }

    /**
     * @param int $first
     *
     * @return $this
     */
    public function setFirstResult($first)
    {
        $this->first = $first;

        return $this;
    }

    /**
     * @param int $max
     *
     * @return $this
     */
    public function setMaxResult($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @return int
     */
    public function getResultCount()
    {
        return $this->resultCount;
    }

    /**
     * @return mixed
     */
    abstract public function getData();

    /**
     * @return mixed
     * @throws OutOfBoundsException
     */
    abstract public function execute();
}
