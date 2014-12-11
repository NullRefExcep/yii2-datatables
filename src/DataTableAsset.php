<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable;


use yii\web\AssetBundle;

class DataTableAsset extends AssetBundle
{
    const STYLING_DEFAULT = 'default';
    const STYLING_BOOTSTRAP = 'bootstrap';
    const STYLING_JUI = 'jqueryui';

    public $styling = self::STYLING_DEFAULT;
    public $fontAwesome = false;
    public $sourcePath = '@bower';

    public $js = [
        'datatables/media/js/jquery.dataTables.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        parent::init();

        switch ($this->styling) {
            case self::STYLING_JUI:
                $this->depends[] = 'yii\jui\JuiAsset';
                $this->css[] = 'datatables-plugins/integration/jqueryui/dataTables.jqueryui.css';
                $this->js[] = 'datatabbles-plugins/integration/jqueryui/dataTables.jqueryui.min.js';
                break;
            case self::STYLING_BOOTSTRAP:
                $this->depends[] = 'yii\bootstrap\BootstrapAsset';
                $this->css[] = 'datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css';
                $this->js[] = 'datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js';
                break;
            default:
                $this->css[] = 'datatables/media/css/jquery.dataTables.min.css';
        }

        if ($this->fontAwesome) {
            $this->css[] = 'dataTables.fontAwesome.css';
        }

        if (YII_ENV_DEV) {
            $this->js[0] = 'datatables/media/js/jquery.dataTables.js';
            $this->css = [
                'datatables/media/css/jquery.dataTables.css',
            ];
        }
    }

} 