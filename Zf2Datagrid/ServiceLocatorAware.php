<?php

namespace Zf2Datagrid;

use Zf2Datagrid\Exception\Table\ServiceLocatorHasNotBeenSetException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ServiceLocatorAware
 *
 * @package Zf2Datagrid
 */
trait ServiceLocatorAware
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return $this
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     * @throws ServiceLocatorHasNotBeenSetException
     */
    public function getServiceLocator()
    {
        if (null == $this->serviceLocator) {
            throw new ServiceLocatorHasNotBeenSetException();
        }

        return $this->serviceLocator;
    }
}