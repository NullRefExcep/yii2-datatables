Yii2 DataTables
===============

Yii2 Widget for [DataTables](https://github.com/DataTables/DataTables) plug-in for jQuery

## Installation

The preferred way to install this extension is through composer.

Either run

```
composer require nullref/yii2-datatables
```
or add
```
"nullref/yii2-datatables": "~1.0"
```
to the require section of your `composer.json` file.

## Basic Usage

```php
<?= \nullref\datatable\DataTable::widget([
    'data' => $dataProvider->getModels(),
    'columns' => [
        'id',
        'name',
        'email'
    ],
]) ?>
```

## DataTable options
Also you can use all [Datatables options](https://datatables.net/reference/option/)

To pass them as widget options:
```php
<?= \nullref\datatable\DataTable::widget([
    'data' => $dataProvider->getModels(),
    'scrollY' => '200px',
    'scrollCollapse' => true,
    'paging' => false,
    'columns' => [
        'name',
        'email'
    ],
]) ?>
```

## Add Links to row

```php
    <?= \nullref\datatable\DataTable::widget([
        'columns' => [
            //other columns
            [
                'class' => 'nullref\datatable\LinkColumn',
                'url' => ['/model/delete'],
                'options' => ['data-confirm' => 'Are you sure you want to delete this item?', 'data-method' => 'post'],
                'queryParams' => ['id'],
                'label' => 'Delete',
            ],
        ],
    ]) ?>
```

Properties of `LinkColumn`: 

- `label` - text placed in `a` tag;
- `url` - will be passed to `Url::to()`;
- `options` - HTML options of the `a` tag;
- `queryParams` - array of params added to `url`
- `render` - custom render js function. E.g:
```php
//config ...
    'columns' => [
        'id',
        //...
        [
            'class' => 'nullref\datatable\LinkColumn',
            'render' => new JsExpression('function render(data, type, row, meta ){
                return "<a href=\"/custom/url/"+row["id"]+"\">View</a>"
            }'),
        ],
    ],
//...
```

## Styling 

`DataTables` supports several styling solutions, including `Bootstrap`, `jQuery UI`, `Foundation`.

```php
'assetManager' => [
    'bundles' => [
        'nullref\datatable\DataTableAsset' => [
            'styling' => \nullref\datatable\DataTableAsset::STYLING_BOOTSTRAP,
        ]
    ],
],
```
## Custom assets
It's posible to use custom styles and scripts:
```php
'nullref\datatable\DataTableAsset' => [
    'sourcePath' => '@webroot/js/plugin/datatables/',
    'js' => [
        'jquery.dataTables-1.10-cust.js',
        'DT_bootstrap.js',
    ],
    'css' => [
        'data-table.css',
    ],
    'styling' => false,
]
```

## Server-side processing

To enable server-side processing add `DataTableAction` to controller like this:

```php
 class SomeController extends Controller
 {
     public function actions()
     {
         return [
             'datatables' => [
                 'class' => 'nullref\datatable\DataTableAction',
                 'query' => Model::find(),
             ],
         ];
     }
     
}
```

Searching and ordering can be customized using closures
```php
public function actions()
{
    return [
         'datatables' => [
             'class' => 'nullref\datatable\DataTableAction',
             'query' => Model::find(),
             'applyOrder' => function($query, $columns, $order) {
                //custom ordering logic
                return $query;
             },
             'applyFilter' => function($query, $columns, $search) {
                //custom search logic
                return $query;
             },
         ],
    ];
}

```

If you need to get some relation data you can call `join` or similar methods from `$query` in `applyFilter` closure.

And add options to widget: 

```php
    <?= \nullref\datatable\DataTable::widget([
        /** ... */
        'serverSide' => true,
        'ajax' => '/site/datatables',
    ]) ?>
```
