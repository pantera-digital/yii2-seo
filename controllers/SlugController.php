<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 8/21/18
 * Time: 4:00 PM
 */

namespace pantera\seo\controllers;


use pantera\seo\models\SeoSlug;
use pantera\seo\models\SeoSlugSearch;
use pantera\seo\Module;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SlugController extends Controller
{
    /* @var Module */
    public $module;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->permissions,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new SeoSlugSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return SeoSlug Loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SeoSlug::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}