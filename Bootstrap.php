<?php

namespace pantera\seo;

use pantera\seo\models\Seo;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\View;

class Bootstrap implements BootstrapInterface
{

    public static $bootstrap = true;

    /** @inheritdoc */
    public function bootstrap($app)
    {
        Event::on(View::className(), View::EVENT_BEGIN_PAGE, function () {
            $url = Yii::$app->request->pathInfo;
            $model = Seo::find()->where(['=', 'url', '/' . $url])->one();
            if ($model) {
                if ($model->description) {
                    Yii::$app->seo->setDescription($model->description);
                }
                if ($model->keywords) {
                    Yii::$app->seo->setKeywords($model->keywords);
                }
                if ($model->title) {
                    Yii::$app->seo->setTitle($model->title);
                }
                if ($model->h1) {
                    Yii::$app->seo->setH1($model->h1);
                }
                if ($model->text) {
                    Yii::$app->seo->setText($model->text);
                }
            }
            $this->registrar();
        });
    }

    private function registrar()
    {
        if (Yii::$app->seo->getDescription()) {
            Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' => Yii::$app->seo->getDescription(),
            ]);
        }
        if (Yii::$app->seo->getKeywords()) {
            Yii::$app->view->registerMetaTag([
                'name' => 'keywords',
                'content' => Yii::$app->seo->getKeywords()
            ]);
        }
        if (Yii::$app->seo->getTitle()) {
            Yii::$app->view->title = Yii::$app->seo->getTitle();
        }
        if (Yii::$app->seo->getH1()) {
            Yii::$app->response->headers->set('seoH1', urlencode(Yii::$app->seo->getH1()));
        }
    }
}
