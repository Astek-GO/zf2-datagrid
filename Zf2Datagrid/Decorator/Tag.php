<?php

namespace Zf2Datagrid\Decorator;

use Zf2Datagrid\Decorator;

/**
 * Class Tag
 *
 * @package Zf2Datagrid\Decorator
 */
class Tag implements Decorator
{
    /**
     * @var string
     */
    protected $htmlTag;

    /**
     * @var array
     */
    protected $attributes;

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

    /**
     * @return string
     */
    protected function getAttributes()
    {
        $attributeTemplate = '%s="%s"';
        $attributes        = [];

        foreach ($this->attributes as $name => $values) {
            $attributes[] = vsprintf($attributeTemplate, [
                $name,
                is_array($values) ? implode(' ', $values) : $values,
            ]);
        }

        return implode(' ', $attributes);
    }
}