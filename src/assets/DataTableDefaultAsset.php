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
    public $sourcePath = '@npm/datatables.net-dt/css';

    public $depends = [
        DataTableBaseAsset::class,
    ];

    public function init()
    {
        parent::init();

        $this->js[] = 'js/dataTables.dataTables' . (YII_ENV_DEV ? '' : '.min') . '.js';
        $this->css[] = 'css/dataTables.dataTables' . (YII_ENV_DEV ? '' : '.min') . '.css';
    }
} 