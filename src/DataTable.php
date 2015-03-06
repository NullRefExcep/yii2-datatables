<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable;


use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\web\JsExpression;

class DataTable extends Widget
{
    const COLUMN_TYPE_DATE = 'date';
    const COLUMN_TYPE_NUM = 'num';
    const COLUMN_TYPE_NUM_FMT = 'num-fmt';
    const COLUMN_TYPE_HTML_NUM = 'html-num';
    const COLUMN_TYPE_HTML_NUM_FMT = 'html-num-fmt';
    const COLUMN_TYPE_STRING = 'string';

    public $id;
    /**
     * @var boolean Feature control DataTables' smart column width handling
     */
    public $autoWidth;
    /**
     * @var bool Feature control deferred rendering for additional speed of initialisation.
     */
    public $deferRender;
    /**
     * @var bool Feature control table information display field
     */
    public $info;
    /**
     * @var bool Use markup and classes for the table to be themed by jQuery UI ThemeRoller.
     */
    public $jQueryUI;
    /**
     * @var bool Feature control the end user's ability to change the paging display length of the table.
     */
    public $lengthChange;
    /**
     * @var bool Feature control ordering (sorting) abilities in DataTables.
     */
    public $ordering;
    /**
     * @var bool Enable or disable table pagination.
     */
    public $paging;
    /**
     * @var bool Feature control the processing indicator.
     */
    public $processing;
    /**
     * @var bool Enable horizontal scrolling
     */
    public $scrollX;
    /**
     * @var bool Enable vertical scrolling
     */
    public $scrollY;
    /**
     * @var bool Feature control search (filtering) abilities
     */
    public $searching;
    /**
     * @var bool Enable server-side filtering, paging and sorting calculations
     */
    public $serverSide;
    /**
     * @var bool Restore table state on page reload
     */
    public $stateSave;
    /**
     * @var array Load data for the table's content from an Ajax source
     */
    public $ajax;
    /**
     * @var array Data to use as the display data for the table.
     */
    public $data;
    /**
     * @var array Set column definition initialisation properties.
     */
    public $columnDefs;
    /**
     * @var array Set column specific initialisation properties.
     */
    public $columns;
    /**
     * @var bool|int|array Delay the loading of server-side data until second draw
     */
    public $deferLoading;
    /**
     * @var bool Destroy any existing table matching the selector and replace with the new options.
     */
    public $destroy;
    /**
     * @var int Initial paging start point
     */
    public $displayStart;
    /**
     * @var string Define the table control elements to appear on the page and in what order
     */
    public $dom;
    /**
     * @var array Change the options in the page length `select` list.
     */
    public $lengthMenu;
    /**
     * @var bool Control which cell the order event handler will be applied to in a column
     */
    public $orderCellsTop;
    /**
     * @var bool Highlight the columns being ordered in the table's body
     */
    public $orderClasses;
    /**
     * @var array Initial order (sort) to apply to the table
     */
    public $order;
    /**
     * @var array Ordering to always be applied to the table
     */
    public $orderFixed;
    /**
     * @var bool Multiple column ordering ability control.
     */
    public $orderMulti;
    /**
     * @var int Change the initial page length (number of rows per page)
     */
    public $pageLength;

    const PAGING_SIMPLE = 'simple';
    const PAGING_SIMPLE_NUMBERS = 'simple_numbers';
    const PAGING_FULL = 'full';
    const PAGING_FULL_NUMBERS = 'full_numbers';

    /**
     * @var string Pagination button display options.
     */
    public $pagingType;
    /**
     * @var string|array Display component renderer types
     */
    public $renderer;
    /**
     * @var bool Retrieve an existing DataTables instance
     */
    public $retrieve;
    /**
     * @var bool Allow the table to reduce in height when a limited number of rows are shown.
     */
    public $scrollCollapse;
    /**
     * @var array
     */
    public $search;
    /**
     * @var array Define an initial search for individual columns.
     */
    public $searchCols;
    /**
     * @var array Set a throttle frequency for searching
     */
    public $searchDelay;
    /**
     * @var int Saved state validity duration
     */
    public $stateDuration;
    /**
     * @var array Set the zebra stripe class names for the rows in the table.
     */
    public $stripeClasses;
    /**
     * @var int Tab index control for keyboard navigation
     */
    public $tabIndex;
    /**
     * @var array
     */
    public $language;
    /**
     * @var string Callback for whenever a TR element is created for the table's body.
     */
    public $createdRow;
    /**
     * @var string Function that is called every time DataTables performs a draw.
     */
    public $drawCallback;
    /**
     * @var string Footer display callback function.
     */
    public $footerCallback;
    /**
     * @var string Number formatting callback function.
     */
    public $formatNumber;
    /**
     * @var string Header display callback function.
     */
    public $headerCallback;
    /**
     * @var string Table summary information display callback.
     */
    public $infoCallback;
    /**
     * @var string Initialisation complete callback.
     */
    public $initComplete;
    /**
     * @var string Pre-draw callback.
     */
    public $preDrawCallback;
    /**
     * @var string Row draw callback.
     */
    public $rowCallback;
    /**
     * @var string Callback that defines where and how a saved state should be loaded.
     */
    public $stateLoadCallback;
    /**
     * @var string State loaded callback.
     */
    public $stateLoaded;
    /**
     * @var string State loaded - data manipulation callback
     */
    public $stateLoadParams;
    /**
     * @var string Callback that defines how the table state is stored and where.
     */
    public $stateSaveCallback;
    /**
     * @var string State save - data manipulation callback
     */
    public $stateSaveParams;
    /**
     * @var array Html options for table
     */
    public $tableOptions = [];

    public function init()
    {
        parent::init();
        DataTableAsset::register($this->view);
        $this->initColumns();
    }

    public function run()
    {
        $id = isset($this->id) ? $this->id : $this->getId();
        echo Html::beginTag('table', ArrayHelper::merge(['id' => $id], $this->tableOptions));

        echo Html::endTag('table');
        $this->view->registerJs('jQuery("#' . $id . '").DataTable(' . Json::encode($this->getParams()) . ');');
    }

    protected function getParams()
    {
        return array_merge($this->getFeatures(), $this->getOptions(), $this->getCallbacks(), [
            'columns' => $this->columns,
            'data' => $this->data,
            'ajax' => $this->ajax,
        ]);
    }

    protected function getFeatures()
    {
        $features = [
            'autoWidth',
            'deferRender',
            'info',
            'jQueryUI',
            'lengthChange',
            'ordering',
            'paging',
            'processing',
            'scrollX',
            'scrollY',
            'searching',
            'serverSide',
            'stateSave',
            'language',
        ];
        $result = [];
        foreach ($features as $attribute) {
            if ($this->$attribute !== null) {
                $result[$attribute] = $this->$attribute;
            }
        }
        return $result;
    }

    protected function getOptions()
    {
        $options = [
            'deferLoading',
            'destroy',
            'displayStart',
            'dom',
            'lengthMenu',
            'orderCellsTop',
            'orderClasses',
            'order',
            'orderFixed',
            'orderMulti',
            'pageLength',
            'pagingType',
            'renderer',
            'retrieve',
            'scrollCollapse',
            'search',
            'searchCols',
            'searchDelay',
            'stateDuration',
            'stripeClasses',
            'tabIndex'
        ];
        $result = [];
        foreach ($options as $attribute) {
            if ($this->$attribute !== null) {
                $result[$attribute] = $this->$attribute;
            }
        }
        return $result;
    }

    protected function getCallbacks()
    {
        $callbacks = [
            'createdRow',
            'drawCallback',
            'footerCallback',
            'formatNumber',
            'headerCallback',
            'infoCallback',
            'initComplete',
            'preDrawCallback',
            'rowCallback',
            'stateLoadCallback',
            'stateLoaded',
            'stateLoadParams',
            'stateSaveCallback',
            'stateSaveParams',
        ];
        $results = [];
        foreach ($callbacks as $attribute) {
            if ($this->$attribute !== null) {
                $results[$attribute] = new JsExpression($this->$attribute);
            }
        }
        return $results;
    }

    protected function initColumns()
    {
        if (isset($this->columns)) {
            foreach ($this->columns as $key => $value) {
                if (is_string($value)) {
                    $this->columns[$key] = ['data' => $value, 'title' => Inflector::camel2words($value)];
                }
                if (isset($value['type'])) {
                    if ($value['type'] == 'link') {
                        $value['class'] = LinkColumn::className();
                    }
                }
                if (isset($value['class'])) {
                    $column = \Yii::createObject($value);
                    $this->columns[$key] = $column;
                }
            }

        }
    }

}
