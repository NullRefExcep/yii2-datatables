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
    public $sourcePath = '@bower/datatables-plugins/integration/jqueryui';

    public $depends = [
        DataTableBaseAsset::class,
    ];

    public function init()
    {
        parent::init();

        $this->depends[] = 'yii\jui\JuiAsset';
        $this->css[] = 'datatables-plugins/integration/jqueryui/dataTables.jqueryui.css';
        $this->js[] = 'datatables-plugins/integration/jqueryui/dataTables.jqueryui' . (YII_ENV_DEV ? '' : '.min') . '.js';
    }

} 