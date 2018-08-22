<?php
namespace pantera\seo;

use pantera\seo\models\Seo;

class SeoTest extends \Codeception\Test\Unit
{
    /**
     * @var \pantera\seo\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testCreateScenarioUrl()
    {
        $seo = new Seo();
        $seo->scenario = Seo::SCENARIO_URL;

        $seo->validate();
        expect($seo->errors)->hasKey('url');

        // url при сохранении должен быть очищен от пробелов в начале и конце
        $seo->url = ' /... ';
        $seo->validate();
        expect($seo->url)->equals('/...');

        // url должен начинаться со слеша
        $seo->url = '...';
        $seo->validate();
        expect($seo->errors)->hasKey('url');

        // корректный url
        $seo->url = '/...';
        $seo->validate();
        expect($seo->errors)->isEmpty();
    }
}