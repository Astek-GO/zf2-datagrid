<?php

namespace Zf2Datagrid\Column;

use Zf2Datagrid\Column;

/**
 * Class Property
 *
 * @package Zf2Datagrid\Column
 */
class Property extends Column
{
    /**
     * @var string
     */
    protected $property;

    /**
     * @param string $key
     * @param string $property
     */
    public function __construct($key, $property)
    {
        $this->property = $property;

        parent::__construct($key);
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }
}