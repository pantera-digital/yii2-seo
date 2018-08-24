<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 8/24/18
 * Time: 4:41 PM
 */

namespace pantera\seo;


use yii\web\AssetBundle;

class ModuleAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/asset';

    public $js = [
        'js/script.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}