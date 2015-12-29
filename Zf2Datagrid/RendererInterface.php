<?php

namespace Zf2Datagrid;

/**
 * Class Renderer
 *
 * @package Zf2Datagrid
 */
interface RendererInterface
{
    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param $emptyMessage
     *
     * @return $this
     */
    public function setEmptyMessage($emptyMessage);

    /**
     * @return string
     */
    public function getEmptyMessage();

    /**
     * @param int $totalCount
     *
     * @return $this
     */
    public function setTotalCount($totalCount);

    /**
     * @param int[] $pageSizes
     *
     * @return $this
     */
    public function setPageSizes(array $pageSizes = []);

    /**
     * @param int $pageSize
     *
     * @return $this
     */
    public function setPageSize($pageSize);

    /**
     * @return int
     */
    public function getPageSize();

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function setColumns(array $columns = []);

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data = []);

    /**
     * @param int $resultCount
     *
     * @return $this
     */
    public function setResultCount($resultCount);

    /**
     * @return int
     */
    public function getResultCount();

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setCurrentPage($page);

    /**
     * @return int
     */
    public function getCurrentPage();

    /**
     * @return float
     */
    public function getLastPageNumber();

    /**
     * @return boolean
     */
    public function hasPagination();

    /**
     * @param boolean $hasPagination
     *
     * @return $this
     */
    public function setHasPagination($hasPagination);

    /**
     * @return boolean
     */
    public function hasSingleSort();

    /**
     * @param boolean $hasSingleSort
     *
     * @return $this
     */
    public function setHasSingleSort($hasSingleSort);

    /**
     * @return mixed
     */
    public function output();

    /**
     * @return mixed
     */
    public function getBody();

    /**
     * @return mixed
     */
    public function getHeader();

    /**
     * @return mixed
     */
    public function getPageCount();

    /**
     * @return mixed
     */
    public function getPageLinks();

    /**
     * @return mixed
     */
    public function getPageSizes();
}
