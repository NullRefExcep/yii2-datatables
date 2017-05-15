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
use yii\db\ActiveRecord;
use yii\web\Response;

/**
 * Action for processing ajax requests from DataTables.
 * @see http://datatables.net/manual/server-side for more info
 * @package nullref\datatable
 */
class DataTableAction extends Action
{
    /**
     * Types of request method
     */
    const REQUEST_METHOD_GET = 'GET';
    const REQUEST_METHOD_POST = 'POST';

    /**
     * @see \nullref\datatable\DataTableAction::getParam
     * @var string
     */
    public $requestMethod = self::REQUEST_METHOD_GET;

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

    /**
     * Format data
     * Signature is following:
     * function ($query, $columns)
     * @var callable
     */
    public $formatData;

    /**
     * Format response
     * Signature is following:
     * function ($response)
     * @var callable
     */
    public $formatResponse;

    /**
     * Check if query is configured
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->query === null) {
            throw new InvalidConfigException(get_class($this) . '::$query must be set.');
        }
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function run()
    {
        /** @var ActiveQuery $originalQuery */
        $originalQuery = $this->query;
        $filterQuery = clone $originalQuery;
        $draw = $this->getParam('draw');
        $filterQuery->where = null;
        $search = $this->getParam('search', ['value' => null, 'regex' => false]);
        $columns = $this->getParam('columns', []);
        $order = $this->getParam('order', []);
        $filterQuery = $this->applyFilter($filterQuery, $columns, $search);
        $filterQuery = $this->applyOrder($filterQuery, $columns, $order);
        if (!empty($originalQuery->where)) {
            $filterQuery->andWhere($originalQuery->where);
        }
        $filterQuery
            ->offset($this->getParam('start', 0))
            ->limit($this->getParam('length', -1));
        $dataProvider = new ActiveDataProvider(['query' => $filterQuery, 'pagination' => ['pageSize' => Yii::$app->request->getQueryParam('length', 10)]]);
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $response = [
                'draw' => (int)$draw,
                'recordsTotal' => (int)$originalQuery->count(),
                'recordsFiltered' => (int)$dataProvider->getTotalCount(),
                'data' => $this->formatData($filterQuery, $columns),
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        return $this->formatResponse($response);
    }

    /**
     * Extract param from request
     * @param $name
     * @param null $defaultValue
     * @return mixed
     */
    protected function getParam($name, $defaultValue = null)
    {
        return $this->requestMethod == self::REQUEST_METHOD_GET ?
            Yii::$app->request->getQueryParam($name, $defaultValue) :
            Yii::$app->request->getBodyParam($name, $defaultValue);
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
            if (array_key_exists('orderable', $columns[$item['column']]) && $columns[$item['column']]['orderable'] === 'false') {
                continue;
            }
            $sort = $item['dir'] == 'desc' ? SORT_DESC : SORT_ASC;
            $query->addOrderBy([$columns[$item['column']]['data'] => $sort]);
        }
        return $query;
    }

    /**
     * @param ActiveQuery $query
     * @param array $columns
     * @return array|ActiveRecord[]
     */
    public function formatData(ActiveQuery $query, $columns)
    {
        if ($this->formatData !== null) {
            return call_user_func($this->formatData, $query, $columns);
        }

        return $query->all();
    }

    /**
     * @param array $response
     * @return array|ActiveRecord[]
     */
    public function formatResponse($response)
    {
        if ($this->formatResponse !== null) {
            return call_user_func($this->formatResponse, $response);
        }

        return $response;
    }
} 
