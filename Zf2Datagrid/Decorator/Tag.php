<?php

namespace Zf2Datagrid\Decorator;

use Zf2Datagrid\DecoratorInterface;
use Zf2Datagrid\HtmlAttributes;

/**
 * Class Tag
 *
 * @package Zf2Datagrid\Decorator
 */
class Tag implements DecoratorInterface
{
    use HtmlAttributes;

    /**
     * @var string
     */
    protected $htmlTag;

    /**
     * @param string $htmlTag
     * @param array  $attributes
     */
    public function __construct($htmlTag, array $attributes = [])
    {
        $this->htmlTag    = $htmlTag;
        $this->attributes = $attributes;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function render($data)
    {
        $template = '<%s %s>%s</%s>';

        return vsprintf($template, [
            $this->htmlTag,
            $this->getAttributes(),
            $data,
            $this->htmlTag,
        ]);
    }
}
