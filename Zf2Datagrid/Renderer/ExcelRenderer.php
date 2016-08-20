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
use Zf2Datagrid\Decorator\Number;
use Zf2Datagrid\Decorator\Percentage;
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
    protected $currentLine = 1;

    /**
     * @var string
     */
    protected $currentColumn = 'A';

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
    public function getHeader()
    {
        $sheet               = $this->workbook->getActiveSheet();
        $this->currentColumn = $this->firstColumn;
        $startLine           = $this->currentLine;

        foreach ($this->columns as $column) {
            $cell = $this->currentColumn . $startLine;

            $sheet->setCellValue($cell, $column->getTitle());
            $this->workbook->getActiveSheet()->getStyle($cell)->applyFromArray($this->getDefaultHeaderStyle());
            $this->currentColumn++;
        }

        $this->currentLine++;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        $sheet               = $this->workbook->getActiveSheet();
        $this->currentColumn = $this->firstColumn;

        foreach ($this->data as $row) {
            foreach ($this->columns as $column) {
                $cell  = $this->currentColumn . $this->currentLine;
                $value = $this->getValueForRow($column, $row);

                $sheet->setCellValue($cell, $value);

                $this->workbook->getActiveSheet()->getStyle($cell)->getAlignment()->setWrapText(true);
                $this->workbook->getActiveSheet()->getColumnDimension($this->currentColumn)->setAutoSize(true);
                $this->workbook->getActiveSheet()->getStyle($cell)->applyFromArray($this->getDefaultDataStyle());

                $this->currentColumn++;
            }

            $this->workbook->getActiveSheet()->getRowDimension($this->currentLine)->setRowHeight(-1);

            $this->currentColumn = $this->firstColumn;
            $this->currentLine++;
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
     * Surcharge pour les cas avec Excel
     *
     * @param object $decorator
     * @param mixed  $value
     *
     * @return float|mixed
     */
    public function applyDecoratorOnValue($decorator, $value)
    {
        if ($decorator instanceof Number) {
            if ($decorator instanceof Percentage) {
                # Avec Excel, les colonnes numériques en pourcent sont
                # automatiquement multipliées par 100 (et oui : pourcent) CQFD
                $value = floatval($value) / 100;
            }

            $this->workbook->getActiveSheet()
                ->getStyle($this->currentColumn . $this->currentLine)
                ->getNumberFormat()
                ->setFormatCode($decorator->getExcelFormat());

            return floatval($value);
        }

        return parent::applyDecoratorOnValue($decorator, $value);
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
