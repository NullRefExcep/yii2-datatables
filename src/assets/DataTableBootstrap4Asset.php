<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable\assets;


use yii\web\AssetBundle;

class DataTableBootstrap4Asset extends AssetBundle
{
    public $depends = [
        'yii\bootstrap4\BootstrapAsset',
        DataTableBaseAsset::class,
    ];

    public function init()
    {
        parent::init();

        $this->sourcePath = '@npm/datatables.net-bs4';
        $this->css[] = 'css/dataTables.bootstrap4' . (YII_ENV_DEV ? '' : '.min') . '.css';
        $this->js[] = 'js/dataTables.bootstrap4' . (YII_ENV_DEV ? '' : '.min') . '.js';
    }

} 