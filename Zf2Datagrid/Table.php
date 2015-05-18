<?php

namespace Zf2Datagrid;

use Zf2Datagrid\Exception\Datagrid\ColumnAlreadyAddedException;
use Zf2Datagrid\Exception\Datagrid\ColumnKeyNotFoundException;
use Zf2Datagrid\Exception\Table\NoRendererHasBeenSetException;
use Zend\Http\Request;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

/**
 * Class Table
 *
 * @package Zf2Datagrid
 */
class Table
{
    /**
     * @var Column[]
     */
    protected $columns = [];

    /**
     * @var int
     */
    protected $paginatorSize = 20;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var Datasource
     */
    protected $datasource;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int[]
     */
    protected $pageSizes = [20, 50, 100, 200, 500];

    /**
     * @var string
     */
    protected $emptyMessage = 'No record found';

    /**
     * @var bool
     */
    protected $storeStateInSession = false;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @param Column $column
     *
     * @return $this
     * @throws ColumnAlreadyAdded
     */
    public function add(Column $column)
    {
        try {
            $this->getColumnByKey($column->getKey());
        } catch (ColumnKeyNotFoundException $cknf) {
            if ($column instanceof ServiceLocatorAwareInterface) {
                $column->setServiceLocator($this->serviceLocator);
            }

            $this->columns[$column->getKey()] = $column;

            return $this;
        }

        throw new ColumnAlreadyAddedException($column);
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return array_values($this->columns);
    }

    /**
     * @return int
     */
    public function getPaginatorSize()
    {
        return $this->paginatorSize;
    }

    /**
     * @param $pageSize
     *
     * @return $this
     */
    public function setPaginatorSize($pageSize)
    {
        $this->paginatorSize = $pageSize;

        return $this;
    }

    /**
     * @param $key
     *
     * @return Column
     * @throws ColumnKeyNotFound
     */
    public function getColumnByKey($key)
    {
        if (! isset($this->columns[$key])) {
            throw new ColumnKeyNotFoundException($key);
        }

        return $this->columns[$key];
    }

    /**
     * @param Datasource $datasource
     *
     * @return $this
     */
    public function setDatasource(Datasource $datasource)
    {
        $this->datasource = $datasource;

        return $this;
    }

    /**
     * @param RendererInterface $renderer
     *
     * @return $this
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @param array $pageSizes
     *
     * @return $this
     */
    public function setPaginatorSizes(array $pageSizes = [])
    {
        $this->pageSizes = $pageSizes;

        return $this;
    }

    /**
     * @return \int[]
     */
    public function getPageSizes()
    {
        return $this->pageSizes;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        if (null == $this->name) {
            return 'zf2-datagrid';
        }

        return $this->name;
    }

    /**
     * @param $emptyMessage
     *
     * @return $this
     */
    public function setEmptyMessage($emptyMessage)
    {
        $this->emptyMessage = $emptyMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmptyMessage()
    {
        return $this->getTranslation($this->emptyMessage);
    }

    /**
     * @return boolean
     */
    public function isStoreStateInSession()
    {
        return $this->storeStateInSession;
    }

    /**
     * @param boolean $storeStateInSession
     *
     * @return $this
     */
    public function setStoreStateInSession($storeStateInSession)
    {
        $this->storeStateInSession = (bool) $storeStateInSession;

        return $this;
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function getTranslation($value)
    {
        if (null === $this->translator) {
            $this->translator = $this->serviceLocator->get('translator');
        }

        return $this->translator->translate($value);
    }

    /**
     * @return Renderer
     * @throws NoRendererHasBeenSetException
     */
    public function getRenderer()
    {
        if (null === $this->renderer) {
            throw new NoRendererHasBeenSetException();
        }

        return $this->renderer;
    }

    /**
     * @return mixed
     * @throws NoRendererHasBeenSetException
     */
    public function render()
    {
        $sortConditions = $this->getSortConditionsFromRequest();
        $pagination     = $this->getPaginationFromRequest();

        if (empty($this->data)) {
            $this->setServiceLocatorToDecorators();

            $this->datasource->setColumns($this->getColumns());
            $this->datasource->setSortConditions($sortConditions);
            $this->datasource->setFirstResult($pagination['first']);
            $this->datasource->setMaxResult($pagination['max']);

            $this->data = $this->datasource->execute();
        }

        $renderer = $this->getRenderer();

        $renderer->setName($this->getName());
        $renderer->setEmptyMessage($this->getEmptyMessage());
        $renderer->setColumns($this->getColumns());
        $renderer->setData($this->data);
        $renderer->setPageSizes($this->getPageSizes());
        $renderer->setPageSize($this->getPaginatorSize());
        $renderer->setResultCount($this->datasource->getResultCount());
        $renderer->setCurrentPage($pagination['page']);

        return $renderer->output();
    }

    /**
     * @param      $name
     * @param null $default
     * @param null $sessionContainerName
     *
     * @return mixed|null|\Zend\Stdlib\ParametersInterface
     */
    private function getParameter($name, $default = null, $sessionContainerName = null)
    {
        /** @var Request $request */
        $request      = $this->serviceLocator->get('Request');
        $requestParam = $request->getQuery($name);

        if ($this->isStoreStateInSession() && null != $sessionContainerName) {
            $container = new Container($sessionContainerName);

            if (null == $requestParam && null != $container->{$name}) {
                $requestParam = $container->{$name};

                if (isset($_GET[$name]) && '' == $_GET[$name]) {
                    $requestParam = $default;
                }

                $container->{$name} = $requestParam;
            }
        }

        return $requestParam;
    }

    /**
     * @param      $name
     * @param      $value
     * @param null $sessionContainerName
     *
     * @return $this
     */
    public function setParameter($name, $value, $sessionContainerName = null)
    {
        if ($this->isStoreStateInSession() && null != $sessionContainerName) {
            $container = new Container($sessionContainerName);

            $container->{$name} = $value;
        }

        return $this;
    }

    /**
     * @return string
     */
    private function getSortSessionContainerName()
    {
        return str_replace('-', '_', $this->getName() . '-sort');
    }

    /**
     * @return string
     */
    private function getPageSessionContainerName()
    {
        return str_replace('-', '_', $this->getName() . '-pagination');
    }

    /**
     * @return array
     */
    private function getSortConditionsFromRequest()
    {
        $requestSortBy  = $this->getParameter('sortBy', null, $this->getSortSessionContainerName());
        $requestSortDir = $this->getParameter('sortDir', null, $this->getSortSessionContainerName());

        $sortConditions = [];

        $sortBy  = (null == $requestSortBy ? [] : explode(',', $requestSortBy));
        $sortDir = (null == $requestSortDir ? [] : explode(',', $requestSortDir));

        for ($i = 0; $i < count($sortBy); $i++) {
            $sortConditions[$sortBy[$i]] = (isset($sortDir[$i]) ? $sortDir[$i] : 'ASC');
        }

        foreach ($this->getColumns() as $column) {
            if ($column->isSortable() && null != $column->getDefaultOrder()) {
                if (! isset($sortConditions[$column->getSortColumn()])) {
                    $sortConditions[$column->getSortColumn()] = $column->getDefaultOrder();
                }
            }
        }

        $this->setCurrentOrderToColumns($sortConditions);

        $this
            ->setParameter('sortBy', $requestSortBy, $this->getSortSessionContainerName())
            ->setParameter('sortDir', $requestSortDir, $this->getSortSessionContainerName());

        return $sortConditions;
    }

    /**
     * @return array
     */
    private function getPaginationFromRequest()
    {
        $requestPage     = $this->getParameter('page', null, $this->getPageSessionContainerName());
        $requestPageSize = $this->getParameter('parPage', null, $this->getPageSessionContainerName());

        $page     = (null == $requestPage || 0 >= $requestPage ? 1 : (int) $requestPage);
        $pageSize = (null == $requestPageSize ? 20 : (int) $requestPageSize);

        $this
            ->setParameter('page', $page, $this->getPageSessionContainerName())
            ->setParameter('parPage', $pageSize, $this->getPageSessionContainerName());

        $this->setPaginatorSize($pageSize);

        return [
            'page'  => $page,
            'first' => ($page - 1) * $this->getPaginatorSize(),
            'max'   => $this->getPaginatorSize(),
        ];
    }

    /**
     * @return $this
     */
    private function setServiceLocatorToDecorators()
    {
        foreach ($this->getColumns() as $column) {
            foreach ($column->getDecorators() as $decorator) {
                if ($decorator instanceof ServiceLocatorAwareInterface) {
                    $decorator->setServiceLocator($this->serviceLocator);
                }
            }
        }

        return $this;
    }

    /**
     * @param array $sortConditions
     *
     * @return $this
     */
    private function setCurrentOrderToColumns(array $sortConditions = [])
    {
        foreach ($this->getColumns() as $column) {
            if ($column->isSortable()) {
                foreach ($sortConditions as $key => $direction) {
                    if ($key == $column->getSortColumn()) {
                        $column->setCurrentOrder($direction);
                    }
                }
            }
        }

        return $this;
    }
}
