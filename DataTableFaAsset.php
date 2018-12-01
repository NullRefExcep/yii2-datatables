<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable;


use yii\web\AssetBundle;

class DataTableFaAsset extends AssetBundle
{
    public $depends = [
        DataTableBaseAsset::class,
    ];

    public function init()
    {
        parent::init();
        $this->css[] = 'dataTables.fontAwesome.css';
    }

} 