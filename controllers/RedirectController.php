<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 8/21/18
 * Time: 4:00 PM
 */

namespace pantera\seo\controllers;


use pantera\seo\models\SeoRedirect;
use pantera\seo\models\SeoRedirectSearch;
use pantera\seo\Module;
use PHPExcel;
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
use function unlink;

class RedirectController extends Controller
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
        SeoRedirect::deleteAll(['IN', SeoRedirect::tableName() . '.id', Yii::$app->request->post('ids')]);
        return $this->redirect(['index']);
    }

    public function actionDeleteAll()
    {
        SeoRedirect::deleteAll();
        return $this->redirect(['index']);
    }

    public function actionExport()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("Seo redirect");
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'URL');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Редирект (URL)');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Код редиректа');
        foreach (SeoRedirect::find()->all() as $key => $model) {
            $key += 2;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $key, $model->from);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $key, $model->to);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $key, $model->code);
        }
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="seo-redirect.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
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
                                $from = str_replace(Yii::$app->request->hostName, "", $cellData['A']);
                                $to = str_replace(Yii::$app->request->hostName, "", $cellData['B']);
                                if ($from && $to) {
                                    $model = SeoRedirect::find()
                                        ->andWhere(['=', SeoRedirect::tableName() . '.from', $from])
                                        ->andWhere(['=', SeoRedirect::tableName() . '.to', $to])
                                        ->one();
                                    if (is_null($model)) {
                                        $model = new SeoRedirect();
                                        $model->from = $from;
                                        $model->to = $to;
                                    }
                                    if (isset($cellData['C'])) $model->code = $cellData['C'];
                                    $model->save();
                                }
                            }
                        }
                    }
                    unlink($resultFileName);
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
        $searchModel = new SeoRedirectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new SeoRedirect();
        $model->loadDefaultValues();
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
     * @return SeoRedirect Loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SeoRedirect::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}