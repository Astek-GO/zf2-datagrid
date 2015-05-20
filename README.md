# Zf2Datagrid - WIP

Permet de créer des datagrid facilement avec Zf2 et Doctrine2.

# Fonctionnalités

* Datasources : Doctrine2 (QueryBuilder)
* Renderer : TwitterBootstrap2
* Decorator > Renderer : Form
* Column : Select/Property
* Decorator > Column : Checkbox/Closure/Date/Icon/Link/NoValue/Route
* Pagination : pagination/order by

Bien sûr vous pouvez étendre vous même chacune des fonctionnalités.

# Installation

## Via composer

Placez-vous à la racine de votre répertoire projet et utilisez la commande :

```
php composer.phar require astek-go/zf2-datagrid:dev-master
```

Vous-êtes maintenant ready to go.

# Documentation

Pour apprendre à utiliser cette librairie vous pouvez consulter la [documentation](/docs/01. Introduction.md) !

# TODO

* Documentation / Exemples WIP
* Gérer les attributs HTML pour les Column (th/td)
* Déplacer le DoctrineObjectDatasource
* D'autres datasource out of the box (TwitterBootstrap3, Foundation...)
* D'autres datasource out of the box (PhpArray, Zend\Db ? autres...)