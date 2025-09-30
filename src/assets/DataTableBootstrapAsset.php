<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable\assets;


use yii\web\AssetBundle;

class DataTableBootstrapAsset extends AssetBundle
{
    public $depends = [
        DataTableBaseAsset::class,
    ];

    public function init()
    {
        parent::init();

        $this->sourcePath = '@npm/datatables.net-bs';
        $this->depends[] = 'yii\bootstrap\BootstrapAsset';
        $this->css[] = 'css/dataTables.bootstrap.min' . (YII_ENV_DEV ? '' : '.min') . '.css';
        $this->js[] = 'js/dataTables.bootstrap.min' . (YII_ENV_DEV ? '' : '.min') . '.js';
    }

} 