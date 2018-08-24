<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 8/21/18
 * Time: 4:00 PM
 */

namespace pantera\seo\controllers;


use pantera\seo\models\Seo;
use pantera\seo\models\SeoUrlSearch;
use pantera\seo\Module;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use PHPExcel_Worksheet_RowIterator;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class UrlController extends Controller
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
                    'import' => ['POST'],
                    'delete-all' => ['POST'],
                    'delete-group' => ['POST'],
                ],
            ],
        ];
    }

    public function actionDeleteGroup()
    {
        Seo::deleteAll(['IN', 'id', Yii::$app->request->post('ids')]);
        return $this->redirect(['index']);
    }

    public function actionDeleteAll()
    {
        Seo::deleteAll(['IS NOT', Seo::tableName() . '.url', null]);
        return $this->redirect(['index']);
    }

    public function actionImport()
    {
        $file = UploadedFile::getInstanceByName('import');
        if ($file) {
            $available_formats = ["xls", "xlsx"];
            ini_set('memory_limit', '12000M');
            ini_set('max_execution_time', 999);
            if (in_array($file->extension, $available_formats)) {
                $filename = time() . "." . $file->extension;
                FileHelper::createDirectory('uploads/seo');
                $resultFileName = 'uploads/seo/' . $filename;
                if ($file->saveAs($resultFileName)) {
                    $xls = PHPExcel_IOFactory::load($resultFileName);
                    $xls->setActiveSheetIndex(0);
                    $sheet = $xls->getActiveSheet();
                    foreach ($sheet->getRowIterator() as $line => $row) {
                        /* @var $row PHPExcel_Worksheet_RowIterator */
                        if ($line >= 2) {
                            $cellIterator = $row->getCellIterator();
                            $cellData = array();
                            foreach ($cellIterator as $cell) {
                                /* @var $cell PHPExcel_Cell */
                                $cellData[$cell->getColumn()] = trim($cell->getCalculatedValue());
                            }
                            if (isset($cellData['A']) && $cellData['A'] != "") {
                                $url = str_replace(Yii::$app->request->hostName, "", $cellData['A']);
                                if ($url != "") {
                                    $model = Seo::find()
                                        ->andWhere(['=', Seo::tableName() . '.url', $url])
                                        ->one();
                                    if (is_null($model)) {
                                        $model = new Seo();
                                        $model->url = $url;
                                    }
                                    $model->setScenario(Seo::SCENARIO_URL);
                                    if (isset($cellData['B'])) $model->title = $cellData['B'];
                                    if (isset($cellData['C'])) $model->description = $cellData['C'];
                                    if (isset($cellData['D'])) $model->keywords = $cellData['D'];
                                    if (isset($cellData['E'])) $model->h1 = $cellData['E'];
                                    if (isset($cellData['F'])) $model->text = $cellData['F'];
                                    $model->save();
                                }
                            }
                        }
                    }
                    Yii::$app->session->setFlash('success', 'Файл загружен');
                } else {
                    Yii::$app->session->setFlash('warning', 'Ошибка загрузки файла');
                }
            } else {
                Yii::$app->session->setFlash('warning', 'Формат файла ' . $file->extension . ' не поддерживается. Только ' . implode(", ", $available_formats));
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Файл не загружен');
        }
        return $this->redirect(['index']);
    }

    public function actionIndex()
    {
        $searchModel = new SeoUrlSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Seo();
        $model->setScenario(Seo::SCENARIO_URL);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario(Seo::SCENARIO_URL);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('update', [
            'model' => $model,
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