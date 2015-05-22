<?php

namespace Zf2Datagrid;

use Zf2Datagrid\Column\Select;
use Zf2Datagrid\Exception\Datasource\InvalidQueryBuilderArgumentException;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class DoctrineObjectDatasource
 *
 * @package Zf2Datagrid
 */
class DoctrineObjectDatasource extends Datasource
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @param $queryBuilder
     *
     * @throws InvalidQueryBuilderArgumentException
     */
    public function __construct($queryBuilder)
    {
        if (! $queryBuilder instanceof QueryBuilder) {
            throw new InvalidQueryBuilderArgumentException();
        }

        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @return QueryBuilder
     */
    public function getData()
    {
        return clone $this->queryBuilder;
    }

    /**
     * @return array
     */
    public function execute()
    {
        $queryBuilder = $this->getData();
        $aliases = [];

        $queryBuilder->resetDQLParts(['select', 'orderBy', 'having']);

        foreach ($this->getColumns() as $column) {
            if ($column instanceof Select) {
                $aliases[] = $column->getAlias();
            }
        }

        foreach (array_unique($aliases) as $alias) {
            $queryBuilder->addSelect($alias);
        }

        foreach ($this->getSortConditions() as $column => $sortDirection) {
            /** @noinspection PhpParamsInspection */
            $queryBuilder->add('orderBy', new OrderBy($column, $sortDirection), true);
        }

        if (null != $this->first) {
            $queryBuilder->setFirstResult($this->first);
        }

        if (null != $this->max) {
            $queryBuilder->setMaxResults($this->max);
        }

        $paginator = new Paginator($queryBuilder);

        $this->resultCount = $paginator->count();

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
