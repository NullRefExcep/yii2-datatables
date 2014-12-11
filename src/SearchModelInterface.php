<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\datatable;


use yii\data\ActiveDataProvider;

interface SearchModelInterface
{
    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params);
} 