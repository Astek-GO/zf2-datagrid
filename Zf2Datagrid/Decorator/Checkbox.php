<?php

namespace Zf2Datagrid\Decorator;

use Zf2Datagrid\Decorator;

/**
 * Class Checkbox
 *
 * @package Zf2Datagrid\Decorator
 */
class Checkbox implements Decorator
{
    /**
     * @var
     */
    private $name;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function render($data)
    {
        $html = '<input type="checkbox" name="%s" value="%s">';

        return vsprintf($html, [
            $this->name,
            $data
        ]);
    }
}