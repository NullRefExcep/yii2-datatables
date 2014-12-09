Yii2 DataTables
===============

Yii2 Widget for [DataTables](https://github.com/DataTables/DataTables) plug-in for jQuery

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
                 'modelClass' => 'app\models\Model',
             ],
         ];
     }
     
}
```

In DataTable options specify: 
```js
{
    "serverSide": true,
    "ajax": "/datatables"
}
```
