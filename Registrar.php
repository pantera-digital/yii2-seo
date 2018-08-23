<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 8/23/18
 * Time: 3:02 PM
 */

namespace pantera\seo;


use pantera\seo\models\Seo;
use Yii;
use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\View;

class Registrar extends BaseObject implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        Event::on(View::className(), View::EVENT_BEFORE_RENDER, function () {
            $url = Yii::$app->request->pathInfo;
            $model = Seo::find()->where(['=', 'url', '/' . $url])->one();
            if ($model) {
                if ($model->description) {
                    Yii::$app->view->registerMetaTag([
                        'name' => 'description',
                        'content' => $model->description,
                    ]);
                }
                if ($model->keywords) {
                    Yii::$app->view->registerMetaTag([
                        'name' => 'keywords',
                        'content' => $model->keywords
                    ]);
                }
                if (!empty($model->title)) {
                    Yii::$app->view->title = $model->title;
                }
            }
        });
    }
}