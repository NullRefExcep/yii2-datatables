<?php
/**
 * @copyright Copyright (c) 2015 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable;

use nullref\datatable\assets\DataTableAsset;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Class DataTable
 * @package nullref\datatable
 * Features
 * @property bool $autoWidth Feature control DataTables' smart column width handling
 * @property bool $deferRender Feature control deferred rendering for additional speed of initialisation.
 * @property bool $info Feature control table information display field
 * @property bool $jQueryUI Use markup and classes for the table to be themed by jQuery UI ThemeRoller.
 * @property bool $lengthChange Feature control the end user's ability to change the paging display length of the table.
 * @property bool $ordering Feature control ordering (sorting) abilities in DataTables.
 * @property bool $paging Enable or disable table pagination.
 * @property bool $processing Feature control the processing indicator.
 * @property bool $scrollX Enable horizontal scrolling
 * @property bool $scrollY Enable vertical scrolling
 * @property bool $searching Feature control search (filtering) abilities
 * @property bool $serverSide Enable server-side filtering, paging and sorting calculations
 * @property bool $stateSave Restore table state on page reload
 * @property array $language
 * Options
 * @property array $ajax Load data for the table's content from an Ajax source
 * @property array $data Data to use as the display data for the table.
 * @property array $columnDefs Set column definition initialisation properties.
 * @property array $columns Set column specific initialisation properties.
 * @property bool|int|array $deferLoading Delay the loading of server-side data until second draw
 * @propert bool $destroy Destroy any existing table matching the selector and replace with the new options.
 * @property int $displayStart Initial paging start point
 * @property string $dom Define the table control elements to appear on the page and in what order
 * @property array $lengthMenu Change the options in the page length `select` list.
 * @property bool $orderCellsTop Control which cell the order event handler will be applied to in a column
 * @property bool $orderClasses Highlight the columns being ordered in the table's body
 * @property array $order Initial order (sort) to apply to the table
 * @property array $orderFixed Ordering to always be applied to the table
 * @property bool $orderMulti Multiple column ordering ability control.
 * @property int $pageLength Change the initial page length (number of rows per page)
 * @property string $pagingType Pagination button display options.
 * @property string|array $renderer Display component renderer types
 * @property bool $retrieve Retrieve an existing DataTables instance
 * @property bool $scrollCollapse Allow the table to reduce in height when a limited number of rows are shown.
 * @property array $search
 * @property array $searchCols Define an initial search for individual columns.
 * @property array $searchDelay Set a throttle frequency for searching
 * @property int $stateDuration Saved state validity duration
 * @property array $stripeClasses Set the zebra stripe class names for the rows in the table.
 * @property int $tabIndex Tab index control for keyboard navigation
 * Callbacks
 * @property string $createdRow Callback for whenever a TR element is created for the table's body.
 * @property string $drawCallback Function that is called every time DataTables performs a draw.
 * @property string $footerCallback Footer display callback function.
 * @property string $formatNumber Number formatting callback function.
 * @property string $headerCallback Header display callback function.
 * @property string $infoCallback Table summary information display callback.
 * @property string $initComplete Initialisation complete callback.
 * @property string $preDrawCallback Pre-draw callback.
 * @property string $rowCallback Row draw callback.
 * @property string $stateLoadCallback Callback that defines where and how a saved state should be loaded.
 * @property string $stateLoaded State loaded callback.
 * @property string $stateLoadParams State loaded - data manipulation callback
 * @property string $stateSaveCallback Callback that defines how the table state is stored and where.
 * @property string $stateSaveParams State save - data manipulation callback
 */
class DataTable extends Widget
{
    const COLUMN_TYPE_DATE = 'date';
    const COLUMN_TYPE_NUM = 'num';
    const COLUMN_TYPE_NUM_FMT = 'num-fmt';
    const COLUMN_TYPE_HTML_NUM = 'html-num';
    const COLUMN_TYPE_HTML_NUM_FMT = 'html-num-fmt';
    const COLUMN_TYPE_STRING = 'string';

    const PAGING_SIMPLE = 'simple';
    const PAGING_SIMPLE_NUMBERS = 'simple_numbers';
    const PAGING_FULL = 'full';
    const PAGING_FULL_NUMBERS = 'full_numbers';
    public $id;
    /**
     * @var array Html options for table
     */
    public $tableOptions = [];
    public $withColumnFilter;
    /** @var bool */
    public $globalVariable = false;
    protected $_options = [];
    protected $_hiddenColumns = [];

    public function init()
    {
        parent::init();
        DataTableAsset::register($this->getView());
        $this->initColumns();
        $this->initData();
    }

    protected function initColumns()
    {
        if (isset($this->_options['columns'])) {
            foreach ($this->_options['columns'] as $key => $value) {
                if (!is_array($value)) {
                    $value = [
                        'class' => DataTableColumn::class,
                        'attribute' => $value,
                        'label' => Inflector::camel2words($value)
                    ];
                }
                if (isset($value['type'])) {
                    if ($value['type'] == 'link') {
                        $value['class'] = LinkColumn::className();
                        unset($value['type']);
                    }
                }
                if (!isset($value['class'])) {
                    $value['class'] = DataTableColumn::className();
                }
                if (isset($value['class'])) {
                    $column = \Yii::createObject($value);
                    if ($column instanceof LinkColumn) {
                        $this->_hiddenColumns = array_merge($this->_hiddenColumns, $column->queryParams);
                    }
                    $this->_options['columns'][$key] = $column;
                }
            }
        }

    }

    private function initData()
    {
        $this->_hiddenColumns = array_unique($this->_hiddenColumns);
        if (array_key_exists('data', $this->_options)) {
            $data = [];

            foreach ($this->_options['data'] as $obj) {
                $row = [];
                foreach ($this->_options['columns'] as $column) {
                    if ($column instanceof DataTableColumn) {
                        $value = ArrayHelper::getValue($obj, $column->data);
                        if (($pos = strrpos($column->data, '.')) !== false) {
                            $keys = explode('.', $column->data);
                            $a = $value;
                            foreach (array_reverse($keys) as $key) {
                                $a = [$key => $a];
                            }
                            $row[$keys[0]] = $a[$keys[0]];
                        } else {
                            $row[$column->data] = $value;
                        }
                    }
                }
                foreach ($this->_hiddenColumns as $column) {
                    $row[$column] = ArrayHelper::getValue($obj, $column);
                }
                if ($row) {
                    $data[] = $row;
                }
            }
            $this->_options['data'] = $data;
        }
    }

    public function run()
    {
        $id = isset($this->id) ? $this->id : $this->getId();
        echo Html::beginTag('table', ArrayHelper::merge(['id' => $id], $this->tableOptions));

        echo Html::endTag('table');

        $globalVariable = $this->globalVariable ? "window[$id] =" : '';
        if ($this->withColumnFilter) {
            $encodedParams = Json::encode($this->getParams());

            $this->getView()->registerJs(
                <<<JS
{
    var params = ${encodedParams};
    var table;
    ${globalVariable} table = jQuery("#${id}").DataTable(params);
    var filterRow = jQuery('<tr></tr>');
    jQuery('#${id} thead tr th').each(function(i) 
    {
        var cell = jQuery('<td></td>')
            .attr('colspan', jQuery(this).attr('colspan'))
            .attr('class', jQuery(this).attr('class'))
            .attr('tabindex', jQuery(this).attr('tabindex'))
            .attr('aria-controls', jQuery(this).attr('aria-controls'))
            .removeClass('sorting sorting_disabled')
            .appendTo(filterRow);

        if (params.columns && params.columns[i] && params.columns[i].renderFilter) {
            cell.html(jQuery.isFunction(params.columns[i].renderFilter) ? params.columns[i].renderFilter(table) : params.columns[i].renderFilter);
        }
    });
    jQuery('#${id} thead').append(filterRow);
    jQuery('#${id} thead tr:eq(1) td').each( function (i) 
    {
        jQuery(':input', this).on('keyup change', function () {
            if (table.column(i).search() !== jQuery(this).val()) {
                table
                    .column(i)
                    .search(jQuery(this).val())
                    .draw();
            }
        } );
    } );
}
JS
            );
        } else {
            $this->getView()->registerJs($globalVariable . 'jQuery("#' . $id . '").DataTable(' . Json::encode($this->getParams()) . ');');
        }
    }

    protected function getParams()
    {
        return $this->_options;
    }

    public function __get($name)
    {
        return isset($this->_options[$name]) ? $this->_options[$name] : null;
    }

    public function __set($name, $value)
    {
        return $this->_options[$name] = $value;
    }

}
