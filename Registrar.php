<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 9/4/18
 * Time: 2:16 PM
 */

namespace pantera\seo;

use pantera\seo\models\SeoRedirect;
use Yii;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\Controller;
use yii\web\View;

class Registrar extends BaseObject implements BootstrapInterface
{
    /** @inheritdoc */
    public function bootstrap($app)
    {
        //Перед рендерингом страницы найдем сео найстроки и установим их
        Event::on(View::className(), View::EVENT_BEGIN_PAGE, function () {
            $url = Yii::$app->request->pathInfo;
            $model = Yii::$app->seo->getSeoModel('/' . $url);
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
        //Перед каждым акшеном смотрим настройки редеректов
        Event::on(Controller::className(), Controller::EVENT_BEFORE_ACTION, function () {
            $model = SeoRedirect::find()
                ->andWhere(['=', SeoRedirect::tableName() . '.from', urldecode(Yii::$app->request->url)])
                ->one();
            if ($model) {
                Yii::$app->response->redirect($model->to, $model->code);
                Yii::$app->end();
            }
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
