<?php

namespace Zf2Datagrid\Renderer;

use BadMethodCallException;
use PHPExcel\Cell;
use PHPExcel\IOFactory;
use PHPExcel\Shared_Font;
use PHPExcel\Style_Alignment;
use PHPExcel\Style_Border;
use PHPExcel\Style_Color;
use PHPExcel\Style_Fill;
use PHPExcel\Workbook;
use Zf2Datagrid\Renderer;
use Zf2Datagrid\Renderer\PHPExcel\ForceCellAsString;

class ExcelRenderer extends Renderer
{
    /**
     * @var Workbook
     */
    protected $workbook;

    /**
     * @var string
     */
    protected $firstColumn = 'A';

    /**
     * @var int
     */
    protected $firstLine = 1;

    public function __construct(Workbook $workbook)
    {
        $this->workbook = $workbook;

        $this->workbook->setActiveSheetIndex(0);
        $this->workbook->getActiveSheet()->setTitle(
            $this->workbook->getProperties()->getTitle()
        );

        Cell::setValueBinder(new ForceCellAsString());
        Shared_Font::setAutoSizeMethod(Shared_Font::AUTOSIZE_METHOD_EXACT);
    }

    /**
     * @return mixed
     */
    public function output()
    {
        $this->getHeader();
        $this->getBody();

        $objWriter = IOFactory::createWriter($this->workbook, 'Excel5');
        $filepath  = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid(uniqid()) . '.xls';

        $objWriter->save($filepath);

        return file_get_contents($filepath);
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        $sheet       = $this->workbook->getActiveSheet();
        $startColumn = 'A';
        $startLine   = 2;

        foreach ($this->data as $row) {
            foreach ($this->columns as $column) {
                $cell  = $startColumn . $startLine;
                $value = $this->getValueForRow($column, $row);

                $sheet->setCellValue($cell, $value);

                $this->workbook->getActiveSheet()->getStyle($cell)->getAlignment()->setWrapText(true);
                $this->workbook->getActiveSheet()->getColumnDimension($startColumn)->setAutoSize(true);
                $this->workbook->getActiveSheet()->getStyle($cell)->applyFromArray($this->getDefaultDataStyle());

                $startColumn++;
            }

            $this->workbook->getActiveSheet()->getRowDimension($startLine)->setRowHeight(-1);

            $startColumn = 'A';
            $startLine++;
        }
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        $sheet       = $this->workbook->getActiveSheet();
        $startColumn = 'A';
        $startLine   = 1;

        foreach ($this->columns as $column) {
            $cell = $startColumn . $startLine;

            $sheet->setCellValue($cell, $column->getTitle());
            $this->workbook->getActiveSheet()->getStyle($cell)->applyFromArray($this->getDefaultHeaderStyle());
            $startColumn++;
        }
    }

    /**
     * @return mixed
     */
    public function getPageCount()
    {
        throw new BadMethodCallException('You should implement this method if you want to use it ;).');
    }

    /**
     * @return mixed
     */
    public function getPageLinks()
    {
        throw new BadMethodCallException('You should implement this method if you want to use it ;).');
    }

    /**
     * @return mixed
     */
    public function getPageSizes()
    {
        throw new BadMethodCallException('You should implement this method if you want to use it ;).');
    }

    /**
     * Renvoi le style par défaut pour le header
     *
     * @return array
     */
    public function getDefaultHeaderStyle()
    {
        return [
            'font'      => [
                'bold'  => true,
                'color' => [
                    'argb' => Style_Color::COLOR_WHITE,
                ]
            ],
            'fill'      => [
                'type'  => Style_Fill::FILL_SOLID,
                'color' => [
                    'argb' => Style_Color::COLOR_BLACK,
                ]
            ],
            'alignment' => [
                'horizontal' => Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => Style_Alignment::VERTICAL_CENTER
            ],
            'borders'   => [
                'outline' => [
                    'style' => Style_Border::BORDER_THIN
                ]
            ]
        ];
    }

    /**
     * Renvoi le style par défaut pour le contenu
     *
     * @return array
     */
    public function getDefaultDataStyle()
    {
        return [
            'borders' => [
                'outline' => [
                    'style' => Style_Border::BORDER_THIN
                ]
            ]
        ];
    }
}
