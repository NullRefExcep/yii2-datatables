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
use yii\db\ActiveRecordInterface;
use yii\web\Response;

class DataTableAction extends Action
{
    /**
     * @var string class name of the model which will be handled by this action.
     * The model class must implement [[ActiveRecordInterface]].
     * This property must be set.
     */
    public $modelClass;

    public function init()
    {
        if ($this->modelClass === null) {
            throw new InvalidConfigException(get_class($this) . '::$modelClass must be set.');
        }
    }

    public function run()
    {
        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $params = Yii::$app->request->getQueryParams();
        $query = $modelClass::find()
            ->offset(Yii::$app->request->getQueryParam('start', 0))
            ->limit(Yii::$app->request->getQueryParam('length', -1));
        $search = Yii::$app->request->getQueryParam('search', ['value' => null, 'regex' => false]);
        $columns = Yii::$app->request->getQueryParam('columns', []);
        $order = Yii::$app->request->getQueryParam('order', []);
        foreach ($order as $key => $item) {
            $sort = $item['dir'] == 'desc' ? SORT_DESC : SORT_ASC;
            $query->addOrderBy([$columns[$key]['data'] => $sort]);
        }
        foreach ($columns as $column) {
            if ($column['searchable'] == 'true') {
                $value = empty($search['value']) ? $column['search']['value'] : $search['value'];
                $query->orFilterWhere(['like', $column['data'], $value]);
            }
        }
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        $response = [
            'draw' => $params['draw'],
            'recordsTotal' => $modelClass::find()->count(),
            'recordsFiltered' => $dataProvider->getTotalCount(),
            'data' => $dataProvider->getModels(),
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
} 