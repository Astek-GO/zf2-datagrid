<?php

namespace Zf2Datagrid\Decorator;

use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zf2Datagrid\RowAware;
use Zf2Datagrid\RowAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * Class Route
 *
 * @package Zf2Datagrid\Decorator
 */
class Route extends Link implements ServiceLocatorAwareInterface, RowAwareInterface
{
    use ServiceLocatorAwareTrait, RowAware;

    /**
     * @var string
     */
    protected $route;

    /**
     * @var array
     */
    protected $params;

    /**
     * @param string $route
     * @param array  $params
     * @param array  $attributes
     */
    public function __construct($route, array $params = [], array $attributes = [])
    {
        $this->route  = $route;
        $this->params = $params;

        parent::__construct($attributes);
    }

    public function render($data)
    {
        $url                      = $this->getServiceLocator()->get('viewhelpermanager')->get('url');
        $this->attributes['href'] = $url($this->route, $this->params);

        return parent::render($data);
    }
}
