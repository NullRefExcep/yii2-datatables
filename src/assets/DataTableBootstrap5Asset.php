<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable\assets;


use yii\web\AssetBundle;

class DataTableBootstrap5Asset extends AssetBundle
{
    public $depends = [
        DataTableBaseAsset::class,
        'yii\bootstrap5\BootstrapAsset',
    ];

    public function init()
    {
        parent::init();

        $this->sourcePath = '@npm/datatables.net-bs5';
        $this->css[] = 'css/dataTables.bootstrap5.min' . (YII_ENV_DEV ? '' : '.min') . '.css';
        $this->js[] = 'js/dataTables.bootstrap5.min' . (YII_ENV_DEV ? '' : '.min') . '.js';
    }

} 