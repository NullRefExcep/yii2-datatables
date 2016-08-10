<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable;


use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Action for processing ajax requests from DataTables.
 * @see http://datatables.net/manual/server-side for more info
 * @package nullref\datatable
 */
class DataTableAction extends Action
{
    /**
     * @var ActiveQuery
     */
    public $query;

    /**
     * Applies ordering according to params from DataTable
     * Signature is following:
     * function ($query, $columns, $order)
     * @var  callable
     */
    public $applyOrder;

    /**
     * Applies filtering according to params from DataTable
     * Signature is following:
     * function ($query, $columns, $search)
     * @var callable
     */
    public $applyFilter;

    public function init()
    {
        if ($this->query === null) {
            throw new InvalidConfigException(get_class($this) . '::$query must be set.');
        }
    }

    public function run()
    {
        /** @var ActiveQuery $originalQuery */
        $originalQuery = $this->query;
        $filterQuery = clone $originalQuery;
        $draw = Yii::$app->request->getQueryParam('draw');
        $filterQuery->where = null;
        $search = Yii::$app->request->getQueryParam('search', ['value' => null, 'regex' => false]);
        $columns = Yii::$app->request->getQueryParam('columns', []);
        $order = Yii::$app->request->getQueryParam('order', []);
        $filterQuery = $this->applyFilter($filterQuery, $columns, $search);
        $filterQuery = $this->applyOrder($filterQuery, $columns, $order);
        if (!empty($originalQuery->where)) {
            $filterQuery->andWhere($originalQuery->where);
        }
        $filterQuery
            ->offset(Yii::$app->request->getQueryParam('start', 0))
            ->limit(Yii::$app->request->getQueryParam('length', -1));
        /* Begin of fix - serverSide pagination - get pagination from server side - Yii
        $dataProvider = new ActiveDataProvider(['query' => $filterQuery, 'pagination' => false]);
        */
        $dataProvider = new ActiveDataProvider(['query' => $filterQuery, 'pagination' => ['pageSize' => Yii::$app->request->getQueryParam('length', 10)] ]);
        // End of fix - serverSide pagination - get pagination from server side - Yii
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $response = [
                'draw' => (int)$draw,
                'recordsTotal' => (int)$originalQuery->count(),
                'recordsFiltered' => (int)$dataProvider->getTotalCount(),
                /* Begin of fix - get actual data from server according to filters, offset and limit
                'data' => $dataProvider->getModels(),
                */
                'data' => $filterQuery->all(),
            	// End of fix - get actual data from server according to filters, offset and limit
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
        return $response;
    }

    /**
     * @param ActiveQuery $query
     * @param array $columns
     * @param array $order
     * @return ActiveQuery
     */
    public function applyOrder(ActiveQuery $query, $columns, $order)
    {
        if ($this->applyOrder !== null) {
            return call_user_func($this->applyOrder, $query, $columns, $order);
        }

        foreach ($order as $key => $item) {
            // Begin of fix - avoid failure on columns not being orderable
        	if (array_key_exists('orderable', $columns[$item['column']]) && $columns[$item['column']]['orderable'] === 'false') {
        		continue;
        	}
        	// End of fix - avoid failure on columns not being orderable
            $sort = $item['dir'] == 'desc' ? SORT_DESC : SORT_ASC;
            $query->addOrderBy([$columns[$item['column']]['data'] => $sort]);
        }
        return $query;
    }

    /**
     * @param ActiveQuery $query
     * @param array $columns
     * @param array $search
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function applyFilter(ActiveQuery $query, $columns, $search)
    {
        if ($this->applyFilter !== null) {
            return call_user_func($this->applyFilter, $query, $columns, $search);
        }

        /** @var \yii\db\ActiveRecord $modelClass */
        $modelClass = $query->modelClass;
        $schema = $modelClass::getTableSchema()->columns;
        foreach ($columns as $column) {
            if ($column['searchable'] == 'true' && array_key_exists($column['data'], $schema) !== false) {
                $value = empty($search['value']) ? $column['search']['value'] : $search['value'];
                $query->orFilterWhere(['like', $column['data'], $value]);
            }
        }
        return $query;
    }
} 
