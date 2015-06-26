<?php

namespace Zf2Datagrid\Datasource;

use ArrayAccess;
use InvalidArgumentException;
use Zend\Tag\Exception\OutOfBoundsException;
use Zf2Datagrid\Datasource;

class PhpArrayDatasource extends Datasource
{
    /**
     * @var array|ArrayAccess
     */
    protected $data = [];

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (! is_array($data)) {
            throw new InvalidArgumentException('You should provide an array.');
        }

        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     * @throws OutOfBoundsException
     */
    public function execute()
    {
        $data              = $this->getData();
        $this->resultCount = count($data);
        $data              = array_slice($data, $this->first, $this->max);

        if ($this->resultCount > 0 && empty($data)) {
            throw new OutOfBoundsException;
        }

        $data = $this->orderData($data);

        return $data;
    }

    protected function orderData(array $data = [])
    {
        if (! empty($this->getSortConditions())) {
            $sort = [];

            foreach ($this->getData() as $k => $v) {
                foreach ($this->getSortConditions() as $column => $direction) {
                    $sort[$column][$k] = $v[$column];
                }
            }

            $sortArgs = [];

            foreach ($this->getSortConditions() as $column => $direction) {
                $sortArgs[] = $sort[$column];
                $sortArgs[] = ('ASC' == $direction ? SORT_ASC : SORT_DESC);
            }

            $sortArgs[] = & $data;

            call_user_func_array('array_multisort', $sortArgs);
        }

        return $data;
    }
}
