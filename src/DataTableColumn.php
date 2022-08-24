<?php
/**
 * @copyright Copyright (c) 2018 Herve Guenot
 * @license MIT
 * @author Herve Guenot <hguenot@gmail.com>
 */

namespace nullref\datatable;

use yii\base\Arrayable;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\JsExpression;

/**
 * Class DataTableColumn
 *
 * @package nullref\datatable
 *
 * Features
 *
 * @property string $type possible values (num, num-fmt, html-num, html-num-fmt, html, string)
 * @property bool   $orderable Using this parameter, you can remove the end user's ability to order upon a column.
 * @property bool   $searchable Using this parameter, you can define if DataTables should include this column in the filterable data in the table
 * @property bool   $visible show and hide columns dynamically through use of this option
 * @property string $width This parameter can be used to define the width of a column, and may take any CSS value (3em, 20px etc).
 * @property string $cellType Change the cell type created for the column - either TD cells or TH cells
 * @property string $contentPadding Add padding to the text content used when calculating the optimal width for a table.
 * @property string $orderDataType
 *
 * Check the full list of supported properties
 *
 * @see: https://datatables.net/reference/option/columns
 */
class DataTableColumn extends Widget implements Arrayable
{
    /**
     * @var string the attribute name associated with this column.
     */
    public $data;

    /**
     * @var string label to be displayed in the header.
     * If it is not set [[\yii\helpers\Inflector::camel2words()]] will be used to get a label.
     */
    public $title;

    /**
     * @var array the HTML attributes for the filter input fields. This property is used in combination with
     * the [[filter]] property. When [[filter]] is not set or is an array, this property will be used to
     * render the HTML attributes for the generated filter input fields.
     *
     * Empty `id` in the default value ensures that id would not be obtained from the model attribute thus
     * providing better performance.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $filterInputOptions = ['class' => 'form-control', 'id' => null];

    /** @var JsExpression Javascript (function or expression) used to display the filter */
    public $renderFilter;

    /** @var JsExpression Javascript (function) used to display the value. */
    public $render;

    /** @var string CSS class for column cell */
    public $sClass = '';

    public $className = '';

    /**
     * Add extra fields to dataset
     * These fields could be used at render function
     *
     * @var array
     */
    public $extraColumns = [];

    /**
     * @var array|null|false Indicating if a filter will be displayed or not.
     *
     * - If this property is not set, a text field will be generated as the filter input with attributes defined
     *   with [[filterInputOptions]].
     * - If this property is an array, a dropdown list will be generated that uses this property value as
     *   the list options.
     * - If you don't want a filter for this data column, set this value to be false.
     */
    protected $filter;

    private $_options = [];

    /**
     * Check if all required properties is set
     */
    public function init()
    {
        parent::init();

        if ($this->data === null && $this->render === null) {
            throw new InvalidConfigException("Either 'data' or 'render' properties must be specified.");
        }

        if ($this->title === null && !is_null($this->attribute)) {
            $this->title = Inflector::camel2words($this->attribute);
        }

        if ($this->render === null) {
            $this->render = $this->getJsRender();
        }

        if ($this->renderFilter === null) {
            $this->renderFilter = $this->getJsFilter();
        }
    }

    /**
     * @return JsExpression
     */
    public function getJsRender()
    {
        if (is_array($this->filter)) {
            $select = "switch (data) {";

            foreach ($this->filter as $key => $value) {
                $key = Html::encode($key);
                $value = Html::encode($value);
                $select .= "\n\tcase '{$key}': return '{$value}';";
            }
            $select .= "\n\tdefault: return data;";
            $select .= "\n}";

            return new JsExpression("function render(data, type, row, meta) { {$select} }");
        } else {
            return new JsExpression("function render(data, type, row, meta){ return data; }");
        }
    }

    /**
     * @return JsExpression
     */
    public function getJsFilter()
    {
        $jsTitle = Html::encode($this->label);
        $jsClass = Html::encode($this->filterInputOptions['class']);
        $jsId = $this->filterInputOptions['id'] ? Html::encode($this->filterInputOptions['id']) : $this->getId();
        if (is_array($this->filter)) {
            $select = "jQuery('<select type=\"text\" placeholder=\"Search {$jsTitle}\"></select>')\n" .
                "\t.addClass('{$jsClass}')\n" .
                "\t.width('100%')\n" .
                "\t.attr('id', '{$jsId}')\n" .
                "\t.append(jQuery('<option value=\"\"></option>'))";

            foreach ($this->filter as $key => $value) {
                $key = Html::encode($key);
                $value = Html::encode($value);
                $select .= "\n\t.append(jQuery('<option></option>', {\n\t\t"
                    . "'value': serverSide ? '{$key}' : '{$value}',\n\t\t"
                    . "'text': '{$value}'\n\t"
                    . "}))";
            }

            return new JsExpression("function(table) { var serverSide = table.page.info().serverSide; return {$select}; }");
        } else if ($this->filter !== false) {
            return new JsExpression(
                "function() {" .
                "return jQuery('<input type=\"text\" placeholder=\"Search {$jsTitle}\" />')\n" .
                "\t.addClass('{$jsClass}')\n" .
                "\t.width('100%')\n" .
                "\t.attr('id', '{$jsId}');\n" .
                "}"
            );
        } else {
            return new JsExpression('jQuery()');
        }
    }

    public function setAttribute($attribute)
    {
        $this->data = $attribute;
    }

    public function getAttribute()
    {
        return $this->data;
    }

    public function setLabel($label)
    {
        $this->title = $label;
    }

    public function getLabel()
    {
        return $this->title;
    }

    /**
     * @return array|false|null
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param array|false|null $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return array
     */
    public function getExtraColumns()
    {
        return $this->extraColumns;
    }

    public function __get($name)
    {
        return $this->canGetProperty($name, true)
            ? parent::__get($name)
            : (isset($this->_options[$name]) ? $this->_options[$name] : null);
    }

    public function __set($name, $value)
    {
        if ($this->canSetProperty($name, true))
            return parent::__set($name, $value);
        else
            return $this->_options[$name] = $value;
    }

    /**
     * @inheritDoc
     */
    public function fields()
    {
        return \Yii::getObjectVars($this);
    }

    /**
     * @inheritDoc
     */
    public function extraFields()
    {
        return $this->_options;
    }

    /**
     * @inheritDoc
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
         return $recursive
            ? array_merge_recursive($this->fields(), $this->extraFields())
            : array_merge($this->fields(), $this->extraFields());
    }
}
