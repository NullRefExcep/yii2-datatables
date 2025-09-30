<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable\assets;


use yii\web\AssetBundle;

class DataTableDefaultAsset extends AssetBundle
{
    public $sourcePath = '@npm/datatables.net-dt';

    public $depends = [
        DataTableBaseAsset::class,
    ];
} 