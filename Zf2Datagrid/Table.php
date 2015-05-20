<?php

namespace Zf2Datagrid;

use Zend\Http\Request;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;
use Zf2Datagrid\Exception\Datagrid\ColumnAlreadyAddedException;
use Zf2Datagrid\Exception\Datagrid\ColumnKeyNotFoundException;
use Zf2Datagrid\Exception\Table\NoRendererHasBeenSetException;

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
     * Ajoute une colonne à la table
     *
     * @param Column $column
     *
     * @return $this
     * @throws ColumnAlreadyAddedException
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
     * Renvoi les colonnes
     *
     * @return Column[]
     */
    public function getColumns()
    {
        return array_values($this->columns);
    }

    /**
     * Indique le nombre d’items max du tableau
     *
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
     * Renvoi le nombre d’items max du tableau (déf: 20)
     *
     * @return int
     */
    public function getPaginatorSize()
    {
        return $this->paginatorSize;
    }

    /**
     * Renvoi une colonne par sa clé
     *
     * @param $key
     *
     * @return Column
     * @throws ColumnKeyNotFoundException
     */
    public function getColumnByKey($key)
    {
        if (! isset($this->columns[$key])) {
            throw new ColumnKeyNotFoundException($key);
        }

        return $this->columns[$key];
    }

    /**
     * Indique le datasource à utiliser
     *
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
     * Indique le renderer à utiliser
     *
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
     * Renvoi le renderer utilisé
     *
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
     * Indique les possibilité de nombre d’items max du tableau
     *
     * @param int[] $pageSizes
     *
     * @return $this
     */
    public function setPaginatorSizes(array $pageSizes = [])
    {
        $this->pageSizes = $pageSizes;

        return $this;
    }

    /**
     * Renvoi les possibilité de nombre d’items max du tableau
     *
     * @return int[]
     */
    public function getPageSizes()
    {
        return $this->pageSizes;
    }

    /**
     * Indique le nom de la table (sous la forme d’un slug)
     *
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
     * Renvoi le nom de la table
     *
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
     * Le texte à afficher quand le tableau est vide (ou sa clé de traduction)
     *
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
     * Renvoi le texte (traduit) à afficher quand le tableau est vide
     *
     * @return string
     */
    public function getEmptyMessage()
    {
        return $this->getTranslation($this->emptyMessage);
    }

    /**
     * Indique si on doit stocker l’état de la pagination en session
     *
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
     * Renvoi true si l’état de la pagination est stockée en session, false sinon
     *
     * @return boolean
     */
    public function isStoreStateInSession()
    {
        return $this->storeStateInSession;
    }

    /**
     * Appelle le datasource puis passe les données au renderer et renvoi le résultat
     *
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
     * Renvoi un texte traduit
     *
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
     * Renvoi un paramètre depuis la Request ou la session
     *
     * @param      $name
     * @param null $default
     * @param null $sessionContainerName
     *
     * @return mixed|null|\Zend\Stdlib\ParametersInterface
     */
    protected function getParameter($name, $default = null, $sessionContainerName = null)
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
     * Set un paramètre en session si stockage en session actif
     *
     * @param      $name
     * @param      $value
     * @param null $sessionContainerName
     *
     * @return $this
     */
    protected function setParameter($name, $value, $sessionContainerName = null)
    {
        if ($this->isStoreStateInSession() && null != $sessionContainerName) {
            $container = new Container($sessionContainerName);

            $container->{$name} = $value;
        }

        return $this;
    }

    /**
     * Renvoi le nom du Container de session pour le sort
     *
     * @return string
     */
    protected function getSortSessionContainerName()
    {
        return str_replace('-', '_', $this->getName() . '-sort');
    }

    /**
     * Renvoi le nom du Container de session pour la pagination
     *
     * @return string
     */
    protected function getPageSessionContainerName()
    {
        return str_replace('-', '_', $this->getName() . '-pagination');
    }

    /**
     * Renvoi les conditions du sort par rapport à la Request/session/paramètre par défaut et mets à jour la session si stockage actif
     *
     * @return array
     */
    protected function getSortConditionsFromRequest()
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
     * Renvoi les conditions de la pagination par rapport à la Request/session/paramètre par défaut et mets à jour la session si stockage actif
     *
     * @return array
     */
    protected function getPaginationFromRequest()
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
     * Set le ServiceLocator à tous les décorateurs de colonnes si besoin
     *
     * @return $this
     */
    protected function setServiceLocatorToDecorators()
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
     * Set l'odre actuel de chaque colonnes si elle sont "sortable"
     *
     * @param array $sortConditions
     *
     * @return $this
     */
    protected function setCurrentOrderToColumns(array $sortConditions = [])
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
