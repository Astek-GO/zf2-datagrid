<?php

namespace Zf2Datagrid\Renderer;

use BadMethodCallException;
use Zf2Datagrid\Column;
use Zf2Datagrid\Renderer;

/**
 * Class CsvRenderer
 *
 * @package Zf2Datagrid\Renderer
 */
class CsvRenderer extends Renderer
{
    /**
     * @var resource
     */
    protected $file;

    /**
     * @var string
     */
    protected $filepath;

    /**
     * @var string
     */
    protected $separator;

    /**
     * @param string $separator
     */
    public function __construct($separator = null)
    {
        $this->separator          = $separator;
        $this->filepath           = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid(uniqid()) . '.csv';
        $this->file               = fopen($this->filepath, 'w+b');
    }

    /**
     * @return string
     */
    public function output()
    {
        $this->getHeader();
        $this->getBody();

        fclose($this->file);

        return file_get_contents($this->filepath);
    }

    /**
     * @return void
     */
    public function getBody()
    {
        foreach ($this->data as $dataRow) {
            $row = [];

            foreach ($this->columns as $column) {
                $row[] = $this->getValueForRow($column, $dataRow);
            }

            fputcsv($this->file, $row, $this->separator);
        }
    }

    /**
     * @return void
     */
    public function getHeader()
    {
        $row = [];

        foreach ($this->columns as $column) {
            $row[] = $column->getTitle();
        }

        fputcsv($this->file, $row, $this->separator);
    }

    /**
     * @param Column $column
     * @param mixed  $row
     *
     * @return string
     */
    protected function getValueForRow(Column $column, $row)
    {
        $value = parent::getValueForRow($column, $row);
        $value = str_replace('<br>', "\r\n", $value);

        return $value;
    }

    /**
     * @return void
     */
    public function getPageCount()
    {
        throw new BadMethodCallException('You should implement this method if you want to use it ;).');
    }

    /**
     * @return void
     */
    public function getPageLinks()
    {
        throw new BadMethodCallException('You should implement this method if you want to use it ;).');
    }

    /**
     * @return void
     */
    public function getPageSizes()
    {
        throw new BadMethodCallException('You should implement this method if you want to use it ;).');
    }
}
