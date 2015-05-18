<?php

namespace Zf2Datagrid;

/**
 * Interface RowAwareInterface
 *
 * @package Zf2Datagrid
 */
interface RowAwareInterface
{
    /**
     * @param $row
     *
     * @return $this
     */
    public function setRow($row);

    /**
     * @return mixed
     */
    public function getRow();
}