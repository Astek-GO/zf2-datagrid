<?php

namespace Zf2Datagrid\Decorator;

use Closure as AnonymousFunction;
use Zf2Datagrid\DecoratorInterface;

/**
 * Class Closure
 *
 * @package Zf2Datagrid\Decorator
 */
class Closure implements DecoratorInterface
{
    /**
     * @var AnonymousFunction
     */
    protected $closure;

    /**
     * @param callable $closure
     */
    public function __construct(AnonymousFunction $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function render($data)
    {
        $method = $this->closure;

        return $method($data);
    }
}
