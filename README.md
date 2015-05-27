# Zf2Datagrid - WIP

Permet de créer des datagrid facilement avec Zf2 et Doctrine2.

# Fonctionnalités

* Datasources : Doctrine2 (QueryBuilder), PhpArray (WIP)
* Renderer : TwitterBootstrap2, Excel, Csv
* Decorator > Renderer : Form
* Column : Select/Property/Index
* Decorator > Column : Checkbox/Closure/Date/Icon/Link/NoValue/Route
* Pagination : pagination/order by

Bien sûr vous pouvez étendre vous même chacune des fonctionnalités.

# Dépendances

* Zend Framework 2
* Doctrine 2

## Optionnelles

* PHPOffice/PHPExcel pour l'export Excel

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
* PhpArray > pagination/order
* Gérer les attributs HTML pour les Column (th/td)
* D'autres renderer out of the box (TwitterBootstrap3, Foundation...)
* D'autres datasource out of the box (Zend\Db ? autres...)
---
Après ça : [Introduction](/docs/01. Introduction.md)