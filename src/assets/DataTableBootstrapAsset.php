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
    public $sourcePath = '@bower/datatables-plugins/integration/bootstrap/3';

    public $depends = [
        DataTableBaseAsset::class,
    ];

    public function init()
    {
        parent::init();

        #$this->depends[] = 'yii\bootstrap\BootstrapAsset';
        $this->css[] = 'dataTables.bootstrap.css';
        $this->js[] = 'dataTables.bootstrap' . (YII_ENV_DEV ? '' : '.min') . '.js';
        $this->depends[] = 'yii\bootstrap4\BootstrapAsset';
    }

} 