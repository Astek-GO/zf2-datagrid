<?php

namespace Zf2Datagrid\Renderer\Decorator;

use Zend\Form\ElementInterface;
use Zend\Form\Form as ZfForm;
use Zend\Form\View\Helper\Form as ZfFormRenderer;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\PhpRenderer;
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

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var ZfForm
     */
    protected $form;

    /**
     * @var ElementInterface[]
     */
    protected $formElements = [];

    public function __construct(Renderer $renderer, ServiceLocatorInterface $serviceLocator, array $attributes = [])
    {
        $this->renderer       = $renderer;
        $this->attributes     = $attributes;
        $this->serviceLocator = $serviceLocator;
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
        return $this->renderer->getName();
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
        return $this->renderer->getEmptyMessage();
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
        return $this->renderer->getPageSize();
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
        return $this->renderer->getResultCount();
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
        return $this->renderer->getCurrentPage();
    }

    /**
     * @return float
     */
    public function getLastPageNumber()
    {
        return $this->renderer->getLastPageNumber();
    }

    /**
     * @param array|ElementInterface $element
     *
     * @return $this
     */
    public function addElement($element)
    {
        $this->getForm()->add($element);

        return $this;
    }

    protected function getForm()
    {
        if (null === $this->form) {
            $this->form = new ZfForm(
                null,
                $this->attributes
            );

            $this->form->setAttributes($this->attributes);
        }

        return $this->form;
    }

    /**
     * @return mixed
     */
    public function output()
    {
        $form = $this->getForm();

        $form->prepare();
        $form->setName(sprintf('form-%s', $this->getName()));

        // TODO : Need refactoring
        $template = '
            %s
                <div class="form-%s-controls">
                    %s
                </div>
                <div class="form-%s-content">
                    %s
                </div>
            %s
        ';

        $formElements = '';

        foreach ($form->getElements() as $element) {
            $formElements .= $this->getFormViewHelper()->formRow($element);
        }

        return vsprintf($template, [
            $this->getFormRenderer()->openTag($form),
            $this->getName(),
            $formElements,
            $this->getName(),
            $this->renderer->output(),
            $this->getFormRenderer()->closeTag($form)
        ]);
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->renderer->getBody();
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->renderer->getHeader();
    }

    /**
     * @return mixed
     */
    public function getPageCount()
    {
        return $this->renderer->getPageCount();
    }

    /**
     * @return mixed
     */
    public function getPageLinks()
    {
        return $this->renderer->getPageLinks();
    }

    /**
     * @return mixed
     */
    public function getPageSizes()
    {
        return $this->renderer->getPageSizes();
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @return Renderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @return ZfFormRenderer
     */
    protected function getFormRenderer()
    {
        return $this->getServiceLocator()->get(ZfFormRenderer::class);
    }

    /**
     * @return PhpRenderer
     */
    protected function getFormViewHelper()
    {
        return $this->getServiceLocator()->get(PhpRenderer::class);
    }

    /**
     * @return boolean
     */
    public function hasPagination()
    {
        return $this->renderer->hasPagination();
    }

    /**
     * @param boolean $hasPagination
     *
     * @return $this
     */
    public function setHasPagination($hasPagination)
    {
        $this->renderer->setHasPagination($hasPagination);

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasSingleSort()
    {
        return $this->renderer->hasSingleSort();
    }

    /**
     * @param boolean $hasSingleSort
     *
     * @return $this
     */
    public function setHasSingleSort($hasSingleSort)
    {
        $this->renderer->setHasPagination($hasSingleSort);

        return $this;
    }
}