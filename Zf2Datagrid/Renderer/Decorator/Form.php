<?php

namespace Zf2Datagrid\Renderer\Decorator;

use Zf2Datagrid\Renderer;
use Zf2Datagrid\RendererInterface;

class Form implements RendererInterface
{
    /**
     * @var Renderer
     */
    protected $renderer;

    /**
     * @var array
     */
    protected $attributes;

    public function __construct(Renderer $renderer, array $attributes = [])
    {
        $this->renderer   = $renderer;
        $this->attributes = $attributes;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        return $this->renderer->setName($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getName();
    }

    /**
     * @param $emptyMessage
     *
     * @return $this
     */
    public function setEmptyMessage($emptyMessage)
    {
        return $this->renderer->setEmptyMessage($emptyMessage);
    }

    /**
     * @return string
     */
    public function getEmptyMessage()
    {
        return $this->getEmptyMessage();
    }

    /**
     * @param int $totalCount
     *
     * @return $this
     */
    public function setTotalCount($totalCount)
    {
        return $this->renderer->setTotalCount($totalCount);
    }

    /**
     * @param int[] $pageSizes
     *
     * @return $this
     */
    public function setPageSizes(array $pageSizes = [])
    {
        return $this->renderer->setPageSizes($pageSizes);
    }

    /**
     * @param int $pageSize
     *
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        return $this->renderer->setPageSize($pageSize);
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->getPageSize();
    }

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function setColumns(array $columns = [])
    {
        return $this->renderer->setColumns($columns);
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data = [])
    {
        return $this->renderer->setData($data);
    }

    /**
     * @param int $resultCount
     *
     * @return $this
     */
    public function setResultCount($resultCount)
    {
        return $this->renderer->setResultCount($resultCount);
    }

    /**
     * @return int
     */
    public function getResultCount()
    {
        return $this->getResultCount();
    }

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setCurrentPage($page)
    {
        return $this->renderer->setCurrentPage($page);
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->getCurrentPage();
    }

    /**
     * @return float
     */
    public function getLastPageNumber()
    {
        return $this->getLastPageNumber();
    }

    /**
     * @return mixed
     */
    public function output()
    {
        // TODO : Need refactoring
        // TODO : add control (control, prepend / append / les deux (masque))
        $template          = '
            <form %s>
                <div class="form-%s-controls">
                    %s
                </div>
                <div class="form-%s-content">
                    %s
                </div>
            </form>
        ';
        $templateAttribute = '%s=%s';
        $attributes        = [];

        foreach ($this->attributes as $name => $value) {
            $attributes[] = vsprintf($templateAttribute, [
                $name,
                $value,
            ]);
        }

        return vsprintf($template, [
            implode(' ', $attributes),
            $this->renderer->getName(),
            '<input type="submit" class="btn btn-primary" value="Send">',
            $this->renderer->getName(),
            $this->renderer->output(),
        ]);
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->getBody();
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->getHeader();
    }

    /**
     * @return mixed
     */
    public function getPageCount()
    {
        return $this->getPageCount();
    }

    /**
     * @return mixed
     */
    public function getPageLinks()
    {
        return $this->getPageLinks();
    }

    /**
     * @return mixed
     */
    public function getPageSizes()
    {
        return $this->getPageSizes();
    }
}