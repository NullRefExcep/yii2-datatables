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
use yii\web\Response;

class DataTableAction extends Action
{
    /**
     * @var ActiveQuery
     */
    public $query;

    public function init()
    {
        if ($this->query === null) {
            throw new InvalidConfigException(get_class($this) . '::$query must be set.');
        }
    }

    public function run()
    {
        $dataProvider = new ActiveDataProvider(['query' => $this->query, 'pagination' => false]);
        /** @var ActiveQuery $originalQuery */
        $originalQuery = $dataProvider->query;
        $actionQuery = clone $originalQuery;
        $draw = Yii::$app->request->getQueryParam('draw');
        $actionQuery->where = null;
        $actionQuery
            ->offset(Yii::$app->request->getQueryParam('start', 0))
            ->limit(Yii::$app->request->getQueryParam('length', -1));
        $search = Yii::$app->request->getQueryParam('search', ['value' => null, 'regex' => false]);
        $columns = Yii::$app->request->getQueryParam('columns', []);
        $order = Yii::$app->request->getQueryParam('order', []);
        foreach ($order as $key => $item) {
            $sort = $item['dir'] == 'desc' ? SORT_DESC : SORT_ASC;
            $actionQuery->addOrderBy([$columns[$item['column']]['data'] => $sort]);
        }
        foreach ($columns as $column) {
            if ($column['searchable'] == 'true') {
                $value = empty($search['value']) ? $column['search']['value'] : $search['value'];
                $actionQuery->orFilterWhere(['like', $column['data'], $value]);
            }
        }
        $dataProvider->query = $actionQuery->andWhere($originalQuery->where);
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $response = [
                'draw' => $draw,
                'recordsTotal' => $originalQuery->count(),
                'recordsFiltered' => $dataProvider->getTotalCount(),
                'data' => $dataProvider->getModels(),
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
        return $response;
    }
} 
