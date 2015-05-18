<?php

namespace Zf2Datagrid;

use Closure;
use Zf2Datagrid\Exception\Decorator\InvalidArgumentException;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * Class Column
 *
 * @package Zf2Datagrid
 */
class Column implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAware;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var Decorator[]
     */
    protected $decorators;

    /**
     * @var bool
     */
    protected $isSortable = true;

    /**
     * @var string|null
     */
    protected $currentOrder = null;

    /**
     * @var string|null
     */
    protected $defaultOrder = null;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key        = $key;
        $this->title      = $key;
        $this->decorators = [];
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function getTranslation($value)
    {
        if (null === $this->translator) {
            $this->translator = $this->getServiceLocator()->get('translator');
        }

        return $this->translator->translate($value);
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getTranslation($this->title);
    }

    /**
     * @param $isSortable
     *
     * @return $this
     */
    public function setIsSortable($isSortable)
    {
        $this->isSortable = (bool) $isSortable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSortable()
    {
        return $this->isSortable;
    }

    /**
     * @param $defaultOrder
     *
     * @return $this
     */
    public function setDefaultOrder($defaultOrder)
    {
        $this->defaultOrder = $defaultOrder;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDefaultOrder()
    {
        return $this->defaultOrder;
    }

    /**
     * @param $currentOrder
     *
     * @return $this
     */
    public function setCurrentOrder($currentOrder)
    {
        $this->currentOrder = $currentOrder;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCurrentOrder()
    {
        return $this->currentOrder;
    }

    /**
     * @return string
     */
    public function getSortColumn()
    {
        return $this->getKey();
    }

    /**
     * @param Decorator|Closure $decorator
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addDecorator($decorator)
    {
        if (! $decorator instanceof Decorator) {
            if (! $decorator instanceof Closure) {
                throw new InvalidArgumentException();
            }
        }

        $this->decorators[] = $decorator;

        return $this;
    }

    /**
     * @param array $decorators type of Decorator or Closure $decorators
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setDecorators(array $decorators = [])
    {
        $this->decorators = [];

        foreach ($decorators as $decorator) {
            $this->addDecorator($decorator);
        }

        return $this;
    }

    /**
     * @return Decorator[]
     */
    public function getDecorators()
    {
        return $this->decorators;
    }
}
