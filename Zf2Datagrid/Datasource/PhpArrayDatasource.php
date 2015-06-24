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

        // TODO : order

        return $data;
    }
}
