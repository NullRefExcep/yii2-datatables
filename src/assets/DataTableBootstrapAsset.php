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

        if (class_exists('yii\bootstrap\BootstrapAsset')) {
            $this->sourcePath = '@npm/datatables.net-bs';
            $this->depends[] = 'yii\bootstrap\BootstrapAsset';
            $this->css[] = 'css/dataTables.bootstrap.min' . (YII_ENV_DEV ? '' : '.min') . '.css';
            $this->js[] = 'js/dataTables.bootstrap.min' . (YII_ENV_DEV ? '' : '.min') . '.js';
        } else if(class_exists('yii\bootstrap4\BootstrapAsset')) {
            $this->sourcePath = '@npm/datatables.net-bs4';
            $this->depends[] = 'yii\bootstrap4\BootstrapAsset';
            $this->css[] = 'css/dataTables.bootstrap4' . (YII_ENV_DEV ? '' : '.min') . '.css';
            $this->js[] = 'js/dataTables.bootstrap4' . (YII_ENV_DEV ? '' : '.min') . '.js';
        } else {
            $this->sourcePath = '@npm/datatables.net-bs5';
            $this->depends[] = 'yii\bootstrap5\BootstrapAsset';
            $this->css[] = 'css/dataTables.bootstrap5' . (YII_ENV_DEV ? '' : '.min') . '.css';
            $this->js[] = 'js/dataTables.bootstrap5' . (YII_ENV_DEV ? '' : '.min') . '.js';
        }
    }

} 