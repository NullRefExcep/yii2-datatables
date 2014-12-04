<?php

namespace nullref\widgets\datatable;


use yii\base\Widget;
use yii\helpers\Html;

class DataTable extends Widget
{
    public $id;

    public function init()
    {
        parent::init();
        DataTableAsset::register($this->view);
    }

    public function run()
    {
        $id = isset($this->id) ? $this->id : $this->getId();
        echo Html::tag('div', '', ['id' => $id]);
        $this->view->registerJs('$("#' . $id . '").DataTable();');
    }

} 