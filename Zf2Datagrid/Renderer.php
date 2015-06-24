<?php

namespace Zf2Datagrid;

use Closure;
use Zf2Datagrid\Decorator\Percentage;

/**
 * Class Renderer
 *
 * @package Zf2Datagrid
 */
abstract class Renderer implements RendererInterface
{
    /**
     * @var int
     */
    protected $totalCount = 0;

    /**
     * @var int[]
     */
    protected $pageSizes = [];

    /**
     * @var int
     */
    protected $pageSize = 0;

    /**
     * @var Column[]
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var string
     */
    protected $emptyMessage = 'No record found';

    /**
     * @var bool
     */
    protected $hasPagination = true;

    /**
     * @var bool
     */
    protected $hasSingleSort = false;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $emptyMessage
     *
     * @return $this
     */
    public function setEmptyMessage($emptyMessage)
    {
        $this->emptyMessage = $emptyMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmptyMessage()
    {
        return $this->emptyMessage;
    }

    /**
     * @param int $totalCount
     *
     * @return $this
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;

        return $this;
    }

    /**
     * @param int[] $pageSizes
     *
     * @return $this
     */
    public function setPageSizes(array $pageSizes = [])
    {
        $this->pageSizes = [];

        foreach ($pageSizes as $pageSize) {
            $this->pageSizes[] = (int) $pageSize;
        }

        return $this;
    }

    /**
     * @param int $pageSize
     *
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = (int) $pageSize;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function setColumns(array $columns = [])
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data = [])
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param int $resultCount
     *
     * @return $this
     */
    public function setResultCount($resultCount)
    {
        $this->totalCount = (int) $resultCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getResultCount()
    {
        return $this->totalCount;
    }

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setCurrentPage($page)
    {
        $this->page = (int) $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->page;
    }

    /**
     * @return float
     */
    public function getLastPageNumber()
    {
        return ceil($this->getResultCount() / $this->getPageSize());
    }

    /**
     * @return boolean
     */
    public function hasPagination()
    {
        return $this->hasPagination;
    }

    /**
     * @param boolean $hasPagination
     *
     * @return $this
     */
    public function setHasPagination($hasPagination)
    {
        $this->hasPagination = $hasPagination;

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasSingleSort()
    {
        return $this->hasSingleSort;
    }

    /**
     * @param boolean $hasSingleSort
     *
     * @return $this
     */
    public function setHasSingleSort($hasSingleSort)
    {
        $this->hasSingleSort = $hasSingleSort;

        return $this;
    }

    /**
     * @param object $decorator
     * @param mixed  $value
     *
     * @return mixed
     */
    public function applyDecoratorOnValue($decorator, $value)
    {
        if ($decorator instanceof Numeric) {
            $this->workbook->getActiveSheet()
                ->getStyle($this->currentColumn . $this->currentLine)
                ->getNumberFormat()
                ->setFormatCode($decorator->getExcelFormat());

            if ($decorator instanceof Percentage) {
                # Avec Excel, les colonnes numériques en pourcent sont
                # automatiquement multipliées par 100 (et oui : pourcent) CQFD
                $value /= 100;
            }

            return floatval($value);
        } elseif ($decorator instanceof Decorator) {
            $value = $decorator->render($value);
        } elseif ($decorator instanceof Closure) {
            $value = $decorator($value);
        }

        return $value;
    }

    /**
     * @param Column $column
     * @param mixed  $row
     *
     * @return mixed|string
     */
    protected function getValueForRow(Column $column, $row)
    {
        if ($column instanceof Column\Property) {
            // This one only gets the property
            $value = $row->{'get' . ucfirst($column->getProperty())}();
        } elseif ($column instanceof Column\Select) {
            // And this one is used to build the query
            $value = $row->{'get' . ucfirst($column->getKey())}();
        } elseif ($column instanceof Column\Index) {
            $value = $row[$column->getKey()];
        } else {
            $value = $column->getKey();
        }

        foreach ($column->getDecorators() as $decorator) {
            $decorator = clone $decorator;

            if ($decorator instanceof RowAwareInterface) {
                $decorator->setRow($row);
            }

            $value = $this->applyDecoratorOnValue($decorator, $value);
        }

        return $value;
    }

    /**
     * @param array $params
     * @param bool  $merge
     *
     * @return string
     */
    protected function getUrlWithThisParams(array $params = [], $merge = true)
    {
        $getParameters = [];
        $parameters    = $merge ? array_merge($_GET, $params) : $params;

        foreach ($parameters as $paramName => $paramValue) {
            $getParameters[] = $paramName . '=' . $paramValue;
        }

        return '?' . implode('&', $getParameters);
    }

    /**
     * @return mixed
     */
    public abstract function output();

    /**
     * @return mixed
     */
    public abstract function getBody();

    /**
     * @return mixed
     */
    public abstract function getHeader();

    /**
     * @return mixed
     */
    public abstract function getPageCount();

    /**
     * @return mixed
     */
    public abstract function getPageLinks();

    /**
     * @return mixed
     */
    public abstract function getPageSizes();
}