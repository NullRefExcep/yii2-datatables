<?php
/**
 * Created by PhpStorm.
 * User: serhiy-vinichuk
 * Date: 04.12.14
 * Time: 10:12
 */

namespace nullref\widgets\datatable;


use yii\web\AssetBundle;

class DataTableAsset extends AssetBundle
{
    public $sourcePath = '@bower/datatables/media';
    public $js = [
        'jquery.dataTables.min.js',
    ];
    public $css = [
        'css/jquery.dataTables.min.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        parent::init();
        if (YII_ENV_DEV) {
            $this->js = [
                'js/jquery.dataTables.js',
            ];
            $this->css = [
                'css/jquery.dataTables.css',
            ];
        }
    }

} 