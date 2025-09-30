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
"nullref/yii2-datatables": "~2.0"
```
to the require section of your `composer.json` file.

### ⚠️ Version 2.0 Requirements
- **PHP**: >= 7.4.0
- **Yii2**: >= 2.0.50
- **DataTables**: v2.0 (automatically installed via NPM assets)

### 📋 Upgrading from v1.x
If you're upgrading from version 1.x, please see our [Migration Guide](MIGRATION.md) for detailed instructions.

## Basic Usage

```php
<?= \nullref\datatable\DataTable::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'name',
        'email'
    ],
]) ?>
```

For backwards compatibility the old usage via `data` is still supported
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
    'withColumnFilter' => true,
]) ?>
```

## Specifies header label and css class for cell

```php
    <?= \nullref\datatable\DataTable::widget([
        'columns' => [
            //other columns
            [
                'data' => 'active',
                'title' => 'Is active',
                'sClass' => 'active-cell-css-class',
            ],
        ],
    ]) ?>
```

## Specifies datatable id

```php
<?= \nullref\datatable\DataTable::widget([
    'data' => $dataProvider->getModels(),
    'id' => 'your-datatable-id'
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
                'linkOptions' => ['data-confirm' => 'Are you sure you want to delete this item?', 'data-method' => 'post'],
                'label' => 'Delete',
            ],
        ],
    ]) ?>
```

Properties of `LinkColumn`: 

- `label` - text placed in `a` tag;
- `title` - header title of column;
- `url` - will be passed to `Url::to()`;
- `linkOptions` - HTML options of the `a` tag;
- `queryParams` - array of params added to `url`, `['id']` by default;
- `render` - custom render js function. E.g:
```php
//config ...
    'columns' => [
        //...
        [
            'class' => 'nullref\datatable\LinkColumn',
            'queryParams' => ['some_id'],
            'render' => new JsExpression('function render(data, type, row, meta ){
                return "<a href=\"/custom/url/"+row["some_id"]+"\">View</a>"
            }'),
        ],
    ],
//...
```

You should pass fields that are using at render function to `queryParams` property

## Column filtering

You can add column filtering functionality by setting option `withColumnFilter` to `true` :

- By default it generates a text field as filter input. 
- It can be replaced by a combo box using `filter` parameter when defining column. It should be a associative array 
  where key is used as filter (value sent to server) and value for cell rendering
- It can be avoided by setting `filter` to false

```php
    <?= \nullref\datatable\DataTable::widget([
        'columns' => [
            'id',
            //...
            [
                'data' => 'active',
                'title' => \Yii::t('app', 'Is active'),
                'filter' => ['true' => 'Yes', 'false' => 'No'],
            ],
            [
                'data' => 'last_connection',
                'filter' => false,
            ],
        ],
    ]) ?>
//...
```

In this example above, filter for `active` field sent to server will contains `'true'` or `'false'` but the cell content 
will be `'Yes'` or `'No'` and the filter will be rendered as a combo box.

No filter will be generated for `last_connection` attrribute.

## Advanced column definition

Cell rendering or filter can be customized using `\nullref\datatable\DataTableColumn` class.

```php
    <?= \nullref\datatable\DataTable::widget([
        'columns' => [
            //other columns
            [
                'class' => 'nullref\datatable\DataTableColumn', // can be omitted
                'data' => 'active',
                'renderFiler' => new \yii\web\JsExpression('function() { ' .
                    'return jQuery(\'<input type="checkbox" value="true"/> Active only\'); ' .
                '}'),
                'render' => new \yii\web\JsExpression('function(data, type, row, meta) { ' .
                    'return jQuery(\'<input type="checkbox" value="true" disabled/>\')' .
                    '    .prop(\'checked\', data == \'true\'); ' .
                    '}'),
            ],
        ],
    ]) ?>
```

## Styling 

`DataTables` supports several styling solutions, including `Bootstrap`, `jQuery UI`, `Foundation`.

```php
'assetManager' => [
    'bundles' => [
        'nullref\datatable\assets\DataTableAsset' => [
            'styling' => \nullref\datatable\assets\DataTableAsset::STYLING_BOOTSTRAP,
        ]
    ],
],
```

### Bootstrap

Bootstrap tables require the class 'table', so you'll need to add the 'table' class using `tableOptions` via the widget config.

```php
<?= \nullref\datatable\DataTable::widget([
    'data' => $dataProvider->getModels(),
    'tableOptions' => [
        'class' => 'table',
    ],
    'columns' => [
        'id',
        'name',
        'email',
    ],
]) ?>
```

## Custom assets
It's possible to use custom styles and scripts:
```php
'nullref\datatable\assets\DataTableAsset' => [
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
                $orderBy = [];
                foreach ($order as $orderItem) {
                    $orderBy[$columns[$orderItem['column']]['data']] = $orderItem['dir'] == 'asc' ? SORT_ASC : SORT_DESC;
                }
                return $query->orderBy($orderBy);
             },
             'applyFilter' => function($query, $columns, $search) {
                //custom search logic
                $modelClass = $query->modelClass;
                $schema = $modelClass::getTableSchema()->columns;
                foreach ($columns as $column) {
                    if ($column['searchable'] == 'true' && array_key_exists($column['data'], $schema) !== false) {
                        $value = empty($search['value']) ? $column['search']['value'] : $search['value'];
                        $query->orFilterWhere(['like', $column['data'], $value]);
                    }
                }
                return $query;
             },
         ],
    ];
}
```

If you need to get some relation data you can call `join` or similar methods from `$query` in `applyFilter` closure.

You may also specify a closure for `query` in `DataTableAction` config if you need complex query like in the following code:
```php
/** ... */
'query' => function() {
    $calculatedValue = calculate_value_for_query();
    
    return Model::find()->where(['calculated_value' => $calculatedValue]);
},
/** ... */
```

And add options to widget: 

```php
<?= \nullref\datatable\DataTable::widget([
    /** ... */
    'serverSide' => true,
    'ajax' => '/site/datatables',
]) ?>
```


## Extra columns

If need to use some custom fields from your model at your render function at column you could pass `extraColumns` param.

It available at DataTable widget, column and server side action definition:

```php
<?= \nullref\datatable\DataTable::widget([
    /** ... */
    'data' => $dataProvider->getModels(),
    'extraColumns' => ['customPrice'],
    'columns' => [
        [
            'title' => 'Custom column',
            'extraColumns' => ['customField'],
            'render' => new JsExpression($customColumnRender),
        ],
    ],
]) ?>
```

```php
class SomeController extends Controller
{
    public function actions()
    {
        return [
            'datatables' => [
                'class' => 'nullref\datatable\DataTableAction',
                'query' => Model::find(),
                'extraColumns' => ['customPrice'],
            ],
        ];
    }
}
```

```php
<?= \nullref\datatable\DataTable::widget([
    /** ... */
    'extraColumns' => ['customPrice'],
]) ?>
```
