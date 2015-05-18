<?php

namespace Zf2Datagrid\Decorator;

use Zf2Datagrid\Decorator;

/**
 * Class Icon
 *
 * @package Zf2Datagrid\Decorator
 */
class Icon implements Decorator
{
    /**
     * @var string
     */
    protected $iconClass;

    /**
     * @param $iconClass
     */
    public function __construct($iconClass)
    {
        $this->iconClass = $iconClass;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function render($data)
    {
        $iconHtml = '<i class="%s">%s</i>';

        return vsprintf($iconHtml, [
            $this->iconClass,
            $data
        ]);
    }
}