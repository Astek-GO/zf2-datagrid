<?php

namespace Zf2Datagrid\Datasource;

use ArrayAccess;
use InvalidArgumentException;
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
     */
    public function execute()
    {
        $data              = $this->getData();
        $this->resultCount = count($data);
        $data              = array_slice($data, $this->first, $this->max);

        // TODO : order

        return $data;
    }
}