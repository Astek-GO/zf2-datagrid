<?php

namespace Zf2Datagrid\Decorator;

use Zf2Datagrid\Decorator;

/**
 * Class Link
 *
 * @package Zf2Datagrid\Decorator
 */
class Link implements Decorator
{
    /**
     * @var array
     */
    protected $attributes;

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

    /**
     * @return string
     */
    protected function getAttributes()
    {
        $attribute  = '%s="%s"';
        $attributes = [];

        foreach ($this->attributes as $name => $value) {
            $attributes[] = vsprintf($attribute, [$name, $value]);
        }

        return implode(' ', $attributes);
    }
}