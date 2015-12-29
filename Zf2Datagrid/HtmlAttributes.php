<?php namespace Zf2Datagrid;

trait HtmlAttributes
{
    /**
     * @var array
     */
    protected $attributes = [];

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
