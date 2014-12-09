<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable;

use yii\helpers\Inflector;

class DataColumn extends \yii\base\Object
{
    const TYPE_DATE = 'date';
    const TYPE_NUM = 'num';
    const TYPE_NUM_FORMATTED = 'num-fmt';
    const TYPE_NUM_HTML = 'html-num';
    const TYPE_NUM_HTML_FORMATTED = 'html-num-fmt';
    const TYPE_STRING = 'string';

    /**
     * @var string Cell type to be created for a column. Either TD cells or TH cells
     */
    public $cellType = 'td';
    /**
     * @var string Class to assign to each cell in the column
     */
    public $className = '';
    /**
     * @var string Add padding to the text content used when calculating the optimal with for a table.
     */
    public $contentPadding = '';
    /**
     * @var string Cell created callback to allow DOM manipulation createdCell( cell, cellData, rowData, rowIndex, colIndex )
     */
    public $createdCell = '';
    /**
     * @var string Set the data source for the column from the rows data object / array
     */
    public $data = '';
    /**
     * @var string Set default, static, content for a column
     */
    public $defaultContent;
    /**
     * @var string Set a descriptive name for a column
     */
    public $name;
    /**
     * @var bool Enable or disable ordering on this column
     */
    public $orderable = true;
    /**
     * @var int|array Define multiple column ordering as the default order for a column
     */
    public $orderData;
    /**
     * @var string Live DOM sorting type assignment. Used by DataTables sorting plug-ins
     */
    public $orderDataType;
    /**
     * @var array Order direction application sequence
     */
    public $orderSequence = ['asc', 'desc'];
    /**
     * @var string|int|array Render (process) the data for use in the table
     */
    public $render;
    /**
     * @var bool Enable or disable filtering on the data in this column
     */
    public $searchable = true;
    /**
     * @var string Set the column title
     */
    public $title;
    /**
     * @var string Set the column type - used for filtering and sorting string processing
     */
    public $type;
    /**
     * @var bool Enable or disable the display of this column
     */
    public $visible = true;
    /**
     * @var string Column width assignment. Can take any CSS value (3em, 20px etc).
     */
    public $width;

    public function __construct($config = [])
    {
        if (is_string($config)) {
            $this->data = $config;
            $this->title = Inflector::humanize($config, true);
            $this->init();
        } else {
            parent::__construct($config);
        }
    }


} 