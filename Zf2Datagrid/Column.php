<?php

namespace Zf2Datagrid;

use Closure;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zf2Datagrid\Exception\Decorator\InvalidArgumentException;

/**
 * Class Column
 *
 * @package Zf2Datagrid
 */
class Column implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait, Translator;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var DecoratorInterface[]
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
     * @var array
     */
    protected $options = [];

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
        return $this->getTranslator()->translate($value);
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
     * @param DecoratorInterface|Closure $decorator
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addDecorator($decorator)
    {
        if (! $decorator instanceof DecoratorInterface) {
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
     * @return DecoratorInterface[]
     */
    public function getDecorators()
    {
        return $this->decorators;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
