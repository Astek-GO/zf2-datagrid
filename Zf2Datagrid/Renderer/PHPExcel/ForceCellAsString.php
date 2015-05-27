<?php

namespace Zf2Datagrid\Renderer\PHPExcel;

use PHPExcel\Cell;
use PHPExcel\Cell_DataType;
use PHPExcel\Cell_DefaultValueBinder;
use PHPExcel\Cell_IValueBinder;
use PHPExcel\Exception;
use PHPExcel\Shared_String;

class ForceCellAsString extends Cell_DefaultValueBinder implements Cell_IValueBinder
{
    /**
     * Force la valeur d'une cellule en chaine de caractère
     *
     * @param Cell $cell
     * @param null $value
     *
     * @return bool
     * @throws Exception
     */
    public function bindValue(Cell $cell, $value = null)
    {
        if (is_string($value)) {
            $value = Shared_String::SanitizeUTF8($value);
            $value = str_replace('<br>', ' ' . chr(10), $value);

            $cell->setValueExplicit($value, Cell_DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
