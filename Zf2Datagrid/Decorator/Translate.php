<?php

namespace Zf2Datagrid\Decorator;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zf2Datagrid\Decorator;
use Zf2Datagrid\RowAware;
use Zf2Datagrid\RowAwareInterface;
use Zf2Datagrid\ServiceLocatorAware;
use Zf2Datagrid\Translator;

/**
 * Class Translate
 *
 * @package Zf2Datagrid\Decorator
 */
class Translate implements Decorator, ServiceLocatorAwareInterface, RowAwareInterface
{
    use ServiceLocatorAware, RowAware, Translator;

    /**
     * @param $data
     *
     * @return string
     */
    public function render($data)
    {
        return $this->getTranslator()->translate($data);
    }
}