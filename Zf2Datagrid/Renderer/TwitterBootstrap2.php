<?php

namespace Zf2Datagrid\Renderer;

use Zf2Datagrid\Column;
use Zf2Datagrid\Decorator;
use Zf2Datagrid\Renderer;

/**
 * Class TwitterBootstrap2
 *
 * @package Zf2Datagrid\Renderer
 */
class TwitterBootstrap2 extends Renderer
{
    /**
     * @var bool
     */
    protected $isFluid = true;

    /**
     * @var array
     */
    protected $classes = [];

    /**
     * @param bool $isFluid
     *
     * @return $this
     */
    public function setIsFluid($isFluid)
    {
        $this->isFluid = (bool) $isFluid;

        return $this;
    }

    /**
     * @param array $classes
     *
     * @return $this
     */
    public function setClasses(array $classes = [])
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * @param $classe
     *
     * @return $this
     */
    public function addClass($classe)
    {
        $this->classes[] = $classe;

        return $this;
    }

    /**
     * @return string
     */
    public function output()
    {
        $template = '<div id="%s"><div class="row%s" id="%s-table-container">%s</div>';
        $params   = [
            $this->getName(),
            $this->isFluid ? '-fluid' : '',
            $this->getName(),
            $this->getTable(),
        ];

        if ($this->hasPagination()) {
            $template .= '<div class="row%s" id="%s-table-pagination">%s</div>';

            $params[] = $this->isFluid ? '-fluid' : '';
            $params[] = $this->getName();
            $params[] = $this->getPageCount() . $this->getPageSizes() . $this->getPageLinks();
        }

        $template .= '</div>';

        return vsprintf($template, $params);
    }

    /**
     * @return string
     */
    protected function getTable()
    {
        $table = sprintf('<table class="table %s">', implode(' ', $this->classes));
        $table .= $this->getHeader();
        $table .= $this->getBody();
        $table .= '</table>';

        return $table;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        $header         = '<thead><tr>';
        $sortConditions = [];

        foreach ($this->columns as $column) {
            if ($column->isSortable() && null != $column->getCurrentOrder()) {
                $sortConditions[$column->getSortColumn()] = $column->getCurrentOrder();
            }
        }

        // TODO : Need refactoring
        foreach ($this->columns as $column) {
            $links = '';

            if ($column->isSortable()) {
                $tempA      = $this->hasSingleSort() ? [] : $sortConditions;
                $tempD      = $this->hasSingleSort() ? [] : $sortConditions;
                $isCurrentA = false;
                $isCurrentD = false;

                $tempA[$column->getSortColumn()] = 'ASC';
                $tempD[$column->getSortColumn()] = 'DESC';

                if ($tempA[$column->getSortColumn()] == $column->getDefaultOrder() || $tempA[$column->getSortColumn()] == $column->getCurrentOrder()) {
                    if ($tempA[$column->getSortColumn()] == $column->getCurrentOrder()) {
                        $isCurrentA = true;
                    }

                    unset($tempA[$column->getSortColumn()]);
                }

                if ($tempD[$column->getSortColumn()] == $column->getDefaultOrder() || $tempD[$column->getSortColumn()] == $column->getCurrentOrder()) {
                    if ($tempD[$column->getSortColumn()] == $column->getCurrentOrder()) {
                        $isCurrentD = true;
                    }

                    unset($tempD[$column->getSortColumn()]);
                }

                $urlA = $this->getUrlWithThisParams([
                    'sortBy'  => implode(',', array_keys($tempA)),
                    'sortDir' => implode(',', array_values($tempA)),
                ]);
                $urlD = $this->getUrlWithThisParams([
                    'sortBy'  => implode(',', array_keys($tempD)),
                    'sortDir' => implode(',', array_values($tempD)),
                ]);

                $links .= vsprintf('<a href="%s"><i class="icon-chevron-up%s"></i></a>', [
                    $urlA,
                    $isCurrentA ? ' icon-white' : '',
                ]);
                $links .= vsprintf('<a href="%s"><i class="icon-chevron-down%s"></i></a>', [
                    $urlD,
                    $isCurrentD ? ' icon-white' : '',
                ]);
            }

            $header .= vsprintf('<th %s>%s%s</th>', [
                $this->getColumnAttributes($column),
                $links,
                $column->getTitle()
            ]);
        }

        $header .= '</tr></thead>';

        return $header;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        $body = '<tbody>';

        if (empty($this->data)) {
            $body .= '<tr><td class="text-center" colspan="' . count($this->columns) . '">' . $this->getEmptyMessage() . '</td></tr>';
        } else {
            foreach ($this->data as $dataRow) {
                $row = '<tr>';

                foreach ($this->columns as $column) {
                    $value = $this->getValueForRow($column, $dataRow);

                    $row .= sprintf('<td>%s</td>', $value);
                }

                $row .= '</tr>';
                $body .= $row;
            }
        }

        $body .= '</tbody>';

        return $body;
    }

    /**
     * @return string
     */
    public function getPageCount()
    {
        $container = '<div class="pagination pagination-left pull-left"><ul><li><span class="btn btn-default disabled">%s</span></li></ul></div>';

        return sprintf(
            $container,
            $this->getResultCount() . ' résultat' . ($this->getResultCount() > 1 ? 's' : '')
        );
    }

    /**
     * @return string
     */
    public function getPageLinks()
    {
        $container = '<div class="pagination pagination-centered"><ul>%s</ul></div>';

        $pageRange = 4;
        $max       = ($this->getCurrentPage() + $pageRange) >= $this->getLastPageNumber() ? $this->getLastPageNumber() : ($this->getCurrentPage() + $pageRange);

        $links = vsprintf('<li%s><a href="%s"> « </a></li>', [
            1 == $this->getCurrentPage() ? ' class="disabled"' : '',
            1 == $this->getCurrentPage() ? '#' : $this->getUrlWithThisParams(['page' => 1]),
        ]);

        for ($pageNumber = ($this->getCurrentPage() - $pageRange); $pageNumber <= $max; $pageNumber++) {
            if (($pageNumber > 0) && ($pageNumber <= $this->getResultCount())) {
                $links .= vsprintf('<li%s><a href="%s">%s</a></li>', [
                    $pageNumber == $this->getCurrentPage() ? ' class="disabled"' : '',
                    $pageNumber == $this->getCurrentPage() ? '#' : $this->getUrlWithThisParams(['page' => $pageNumber]),
                    $pageNumber,
                ]);
            }
        }

        $links .= vsprintf('<li%s><a href="%s"> » </a></li>', [
            false ? ' class="disabled"' : '',
            $this->getCurrentPage() >= $this->getLastPageNumber() ? '#' : $this->getUrlWithThisParams(['page' => $this->getLastPageNumber()]),
        ]);

        return sprintf($container, $links);
    }

    /**
     * @return string
     */
    public function getPageSizes()
    {
        $container = '<div class="pagination pagination-right pull-right"><ul>%s</ul></div>';
        $list      = '';

        foreach ($this->pageSizes as $pageSize) {
            if ($this->totalCount < $pageSize) {
                $pageSize = $this->totalCount;

                $list .= $this->generatePageSizeLink($pageSize);

                break;
            }

            $list .= $this->generatePageSizeLink($pageSize);
        }

        return sprintf($container, $list);
    }

    /**
     * @param int $pageSize
     *
     * @return string
     */
    protected function generatePageSizeLink($pageSize)
    {
        return vsprintf('<li%s><a href="%s">%s</a></li>', [
            ($pageSize == $this->getPageSize() ? ' class="active"' : ''),
            ($pageSize == $this->getPageSize() ? '#' : $this->getUrlWithThisParams(['parPage' => $pageSize])),
            $pageSize,
        ]);
    }

    /**
     * @param Column $column
     *
     * @return string
     */
    protected function getColumnAttributes(Column $column)
    {
        $classes = null;
        $options = $column->getOptions();

        if (isset($options['attributes']) && is_array($options['attributes'])) {
            $attribute  = '%s="%s"';
            $attributes = [];

            foreach ($options['attributes'] as $name => $value) {
                $attributes[] = vsprintf($attribute, [$name, $value]);
            }

            return implode(' ', $attributes);
        }

        return '';
    }
}