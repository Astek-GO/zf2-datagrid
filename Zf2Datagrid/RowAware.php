<?php

namespace Zf2Datagrid;

/**
 * Class RowAware
 *
 * @package Zf2Datagrid
 */
trait RowAware
{
    /**
     * @var mixed
     */
    protected $row;

    /**
     * @param mixed $row
     *
     * @return $this
     */
    public function setRow($row)
    {
        $this->row = $row;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRow()
    {
        return $this->row;
    }
}
