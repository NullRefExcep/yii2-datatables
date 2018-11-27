<?php
/*
 * =============================================================================
 * This file is part of hguenot/base-app project.
 * =============================================================================
 * Copyright 2018 - Herve Guenot - https://github.com/hguenot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * =============================================================================
 * 
 * Date: 25/11/2018
 * Time: 07:37
 */

namespace nullref\datatable;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\JsExpression;

class DataTableColumn extends Widget
{
    /**
     * @var string the attribute name associated with this column. When neither [[content]] nor [[value]]
     * is specified, the value of the specified attribute will be retrieved from each data model and displayed.
     *
     * Also, if [[label]] is not specified, the label associated with the attribute will be displayed.
     */
    public $attribute;
    /**
     * @var string label to be displayed in the [[header|header cell]] and also to be used as the sorting
     * link label when sorting is enabled for this column.
     * If it is not set and the models provided by the GridViews data provider are instances
     * of [[\yii\db\ActiveRecord]], the label will be determined using [[\yii\db\ActiveRecord::getAttributeLabel()]].
     * Otherwise [[\yii\helpers\Inflector::camel2words()]] will be used to get a label.
     */
    public $label;
    /**
     * @var string|array|null|false the HTML code representing a filter input (e.g. a text field, a dropdown list)
     * that is used for this data column. This property is effective only when [[GridView::filterModel]] is set.
     *
     * - If this property is not set, a text field will be generated as the filter input with attributes defined
     *   with [[filterInputOptions]]. See [[\yii\helpers\BaseHtml::activeInput]] for details on how an active
     *   input tag is generated.
     * - If this property is an array, a dropdown list will be generated that uses this property value as
     *   the list options.
     * - If you don't want a filter for this data column, set this value to be false.
     */
    public $filter;
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

    public function getDataTableConfig()
    {
        return [
                'data' => $this->attribute,
                'title' => $this->label ?? Inflector::camel2words($this->attribute),
                'renderFilter' => $this->getJsFilter(),
                'render' => $this->getJsRender(),
        ];
    }

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
                $select .= "\n\t.append(jQuery('<option value=\"{$key}\">{$value}</option>'))";
            }

            return new JsExpression("function() { return {$select}; }");
        } else {
            return new JsExpression(
                    "function() {" .
                    "return jQuery('<input type=\"text\" placeholder=\"Search {$jsTitle}\" />')\n" .
                    "\t.addClass('{$jsClass}')\n" .
                    "\t.width('100%')\n" .
                    "\t.attr('id', '{$jsId}');\n" .
                    "}"
            );
        }
    }

    public function getJsRender()
    {
        $jsTitle = Html::encode($this->label);
        $jsClass = Html::encode($this->filterInputOptions['class']);
        $jsId = $this->filterInputOptions['id'] ? Html::encode($this->filterInputOptions['id']) : $this->getId();
        if (is_array($this->filter)) {
            $select = "switch (data) {";

            foreach ($this->filter as $key => $value) {
                $key = Html::encode($key);
                $value = Html::encode($value);
                $select .= "\n\tcase '{$key}': return '{$value}';";
            }
            $select .= "\n\tdefault: return data;";
            $select .= "\n}";

            return new JsExpression("function render( data, type, row, meta ) { {$select} }");
        } else {
            return new JsExpression("function render( data, type, row, meta ){ console.log(arguments); return data; }");
        }
    }

}