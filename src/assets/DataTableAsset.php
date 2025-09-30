<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable\assets;


use yii\web\AssetBundle;

class DataTableAsset extends AssetBundle
{
    const STYLING_DEFAULT = 'default';
    const STYLING_BOOTSTRAP = 'bootstrap';
    const STYLING_JUI = 'jqueryui';

    public $styling = self::STYLING_DEFAULT;
    public $fontAwesome = false;

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        parent::init();

        switch ($this->styling) {
            case self::STYLING_JUI:
                $this->depends[] = DataTableJuiAsset::class;
                break;
            case self::STYLING_BOOTSTRAP:
                $this->depends[] = DataTableBootstrapAsset::class;
                break;
            case self::STYLING_DEFAULT:
            default:
                $this->depends[] = DataTableBaseAsset::class;
                $this->depends[] = DataTableDefaultAsset::class;
                break;
        }

        if ($this->fontAwesome) {
            $this->depends[] = DataTableFaAsset::class;
        }
    }

} 