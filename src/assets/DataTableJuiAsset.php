<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable\assets;


use yii\web\AssetBundle;

class DataTableJuiAsset extends AssetBundle
{
    public $sourcePath = '@npm/datatables.net-jqui';

    public $depends = [
        DataTableBaseAsset::class,
    ];

    public function init()
    {
        parent::init();

        $this->depends[] = 'yii\jui\JuiAsset';
        $this->css[] = 'css/dataTables.jqueryui' . (YII_ENV_DEV ? '' : '.min') . '.css';
        $this->js[] = 'js/dataTables.jqueryui' . (YII_ENV_DEV ? '' : '.min') . '.js';
    }

} 