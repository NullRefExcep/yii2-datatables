<?php
/**
 * @copyright Copyright (c) 2018 Herve Guenot
 * @license MIT
 * @author Herve Guenot <hguenot@gmail.com>
 */
namespace nullref\datatable;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\JsExpression;

class DataTableColumn extends Widget
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
     * @var array|null|false Indicating if a filter will be displayed or not.
     *
     * - If this property is not set, a text field will be generated as the filter input with attributes defined
     *   with [[filterInputOptions]].
     * - If this property is an array, a dropdown list will be generated that uses this property value as
     *   the list options.
     * - If you don't want a filter for this data column, set this value to be false.
     */
    private $filter;

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

    public function init() {
        parent::init();

        if ($this->data === null) {
            throw new InvalidConfigException(get_class($this) . '::$data must be set.');
        }

        if ($this->title === null) {
            $this->title = Inflector::camel2words($this->attribute);
        }

        if ($this->render === null) {
            $this->render= $this->getJsRender();
        }

        if ($this->renderFilter === null) {
            $this->renderFilter =  $this->getJsFilter();
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
	public function getFilter() {
		return $this->filter;
	}

	/**
	 * @param array|false|null $filter
	 */
	public function setFilter($filter): void {
		$this->filter = $filter;
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

            return new JsExpression("function render(data, type, row, meta) { {$select} }");
        } else {
            return new JsExpression("function render(data, type, row, meta){ return data; }");
        }
    }

}