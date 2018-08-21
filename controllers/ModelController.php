<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 8/21/18
 * Time: 4:00 PM
 */

namespace pantera\seo\controllers;


use pantera\seo\models\Seo;
use pantera\seo\models\SeoModelSearch;
use pantera\seo\models\SeoSearch;
use pantera\seo\Module;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ModelController extends Controller
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
        $searchModel = new SeoModelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $modelsNames = Seo::find()
            ->select('modelName')
            ->distinct()
            ->all();
        $modelsNames = ArrayHelper::map($modelsNames, 'modelName', 'modelName');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelNames' => $modelsNames,
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
     * @return Seo Loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Seo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}