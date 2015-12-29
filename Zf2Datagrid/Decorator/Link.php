<?php

namespace Zf2Datagrid\Decorator;

use Zf2Datagrid\DecoratorInterface;
use Zf2Datagrid\HtmlAttributes;

/**
 * Class Link
 *
 * @package Zf2Datagrid\Decorator
 */
class Link implements DecoratorInterface
{
    use HtmlAttributes;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function render($data)
    {
        $linkHtml = '<a %s>%s</a>';

        return vsprintf($linkHtml, [
            $this->getAttributes(),
            $data,
        ]);
    }
}
