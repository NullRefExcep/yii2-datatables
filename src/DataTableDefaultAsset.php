<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable;


use yii\web\AssetBundle;

class DataTableDefaultAsset extends AssetBundle
{
    public $sourcePath = '@bower/datatables/media/css';

    public $depends = [
        DataTableBaseAsset::class,
    ];

    public function init()
    {
    }

} 