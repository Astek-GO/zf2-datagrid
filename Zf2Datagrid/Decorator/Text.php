<?php

namespace Zf2Datagrid\Decorator;

use Zf2Datagrid\DecoratorInterface;
use Zf2Datagrid\HtmlAttributes;

/**
 * Class Text
 *
 * @package Zf2Datagrid\Decorator
 */
class Text implements DecoratorInterface
{
    use HtmlAttributes;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param       $name
     * @param array $attributes
     */
    public function __construct($name, array $attributes = [])
    {
        $this->name       = $name;
        $this->attributes = $attributes;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function render($data)
    {
        $html = '<input type="text" name="%s" value="%s" %s>';

        return vsprintf($html, [
            $this->name,
            $data,
            $this->getAttributes(),
        ]);
    }
}
