<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable\assets;


use yii\web\AssetBundle;

class DataTableBaseAsset extends AssetBundle
{
    const STYLING_DEFAULT = 'default';
    const STYLING_BOOTSTRAP = 'bootstrap';
    const STYLING_JUI = 'jqueryui';

    public $styling = self::STYLING_DEFAULT;
    public $fontAwesome = false;
    public $sourcePath = '@bower/datatables/media';

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        parent::init();
        $this->js[] = 'js/jquery.dataTables' . (YII_ENV_DEV ? '' : '.min') . '.js';
        $this->css[] = 'css/jquery.dataTables' . (YII_ENV_DEV ? '' : '.min') . '.css';

        if ($this->fontAwesome) {
            $this->css[] = 'dataTables.fontAwesome.css';
        }
    }

} 