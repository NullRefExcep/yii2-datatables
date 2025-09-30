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
   const STYLING_BOOTSTRAP4 = 'bootstrap4';
   const STYLING_BOOTSTRAP5 = 'bootstrap5';
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
            case self::STYLING_BOOTSTRAP4:
                $this->depends[] = DataTableBootstrap4Asset::class;
                break;
            case self::STYLING_BOOTSTRAP5:
                $this->depends[] = DataTableBootstrap5Asset::class;
                break;
            case self::STYLING_DEFAULT:
                $this->depends[] = DataTableBaseAsset::class;
                break;
            default;
        }

        if ($this->fontAwesome) {
            $this->depends[] = DataTableFaAsset::class;
        }
    }

} 