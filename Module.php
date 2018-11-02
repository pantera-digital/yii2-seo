<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 8/21/18
 * Time: 3:58 PM
 */

namespace pantera\seo;


use Yii;

class Module extends \yii\base\Module
{
    /* @var array Массив ролей которым доступна админка */
    public $permissions = ['@'];

    public function init()
    {
        parent::init();
        ModuleAsset::register(Yii::$app->view);
    }

    public function getMenuItems()
    {
        return [['label' => 'SEO', 'url' => '#', 'icon' => 'line-chart', 'items' => [
            ['label' => 'Presets', 'url' => ['/seo/presets']],
            ['label' => 'Models', 'url' => ['/seo/model']],
            ['label' => 'Urls', 'url' => ['/seo/url']],
            ['label' => 'Replacements', 'url' => ['/seo/replacement']],
            ['label' => 'NotFound', 'url' => ['/seo/not-found']],
            ['label' => 'Redirect', 'url' => ['/seo/redirect']],
            ['label' => 'Slug', 'url' => ['/seo/slug']],
        ]]];
    }
}
