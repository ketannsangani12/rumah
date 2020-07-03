<?php

namespace app\controllers;

use app\models\AgreementTemplates;
use kartik\mpdf\Pdf;
use Yii;
use app\models\BookingRequests;
use app\models\BookingRequestsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BookingrequestsController implements the CRUD actions for BookingRequests model.
 */
class BookingrequestsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all BookingRequests models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookingRequestsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BookingRequests model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BookingRequests model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate1()
    {
        $model = new BookingRequests();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BookingRequests model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'updatecreditscore';
        if ($model->load(Yii::$app->request->post())) {
            $model->report = \yii\web\UploadedFile::getInstance($model, 'report');
            if($model->validate()){
                $newFileName = \Yii::$app->security
                        ->generateRandomString().'.'.$model->report->extension;
               $model->credit_score_report = $newFileName;
               if($model->status=='New'){
                   $model->status = 'Pending';
               }
               $model->updated_at = date('Y-m-d H:i:s');
               if($model->save()){
                   $model->report->saveAs('uploads/creditscorereports/' . $newFileName);
                   return $this->redirect(['index']);

               }else{
                   return $this->render('update', [
                       'model' => $model,
                   ]);
               }
            }else{
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }else{
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    public function actionChoosetemplate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'choosetemplate';
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()) {
                $model->status = 'Agreement Processed';
                $model->updated_at = date('Y-m-d H:i:s');
                if($model->save()){
                    return $this->redirect(['index']);

                }else{
                    return $this->render('choosetemplate', [
                        'model' => $model,
                    ]);
                }
            }else{
                return $this->render('choosetemplate', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('choosetemplate', [
                'model' => $model,
            ]);
        }
    }
    public function actionPrintagreement($id)
    {
        $model = $this->findModel($id);
        $content = $model->document_content;
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',

            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['defaultheaderline' => 0,  //for header
                'defaulfooterline' => 0  //for footer
        ],
            // call mPDF methods on the fly
            'methods' => [
                //'SetHeader' => ['Krajee Report Header'],
                //'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }
    public function actionContent()
    {
        if(!empty($_POST)){
            $template_id = $_POST['template'];
            $request_id = $_POST['request_id'];
            $templatedetails = AgreementTemplates::findOne($template_id);
            $requestdetails = BookingRequests::findOne($request_id);
            $content = Yii::$app->common->replaceLetterContent($templatedetails->document,$requestdetails);
            echo json_encode(array('content'=>$content));
            exit;
        }
    }
    public function actionUploadagreement($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'uploadagreement';
        if ($model->load(Yii::$app->request->post())) {
            $model->agreement = \yii\web\UploadedFile::getInstance($model, 'agreement');
            $model->movein = \yii\web\UploadedFile::getInstance($model, 'movein');
            if($model->validate()) {
                $newFileName = \Yii::$app->security
                        ->generateRandomString().'.'.$model->agreement->extension;
                $newFileName1 = \Yii::$app->security
                        ->generateRandomString().'.'.$model->movein->extension;
                $model->agreement_document = $newFileName;
                $model->movein_document = $newFileName1;
                if($model->status=='Agreement Processed'){
                    $model->status = 'Payment Requested';
                }
                $model->updated_at = date('Y-m-d H:i:s');
                if($model->save()){
                    $model->agreement->saveAs('uploads/agreements/' . $newFileName);
                    $model->movein->saveAs('uploads/moveinout/' . $newFileName1);
                    return $this->redirect(['index']);

                }else{
                    return $this->render('uploadagreement', [
                        'model' => $model,
                    ]);
                }
            }else{
                return $this->render('uploadagreement', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('uploadagreement', [
                'model' => $model,
            ]);
        }
    }
    public function actionUploadmoveout($id)
    {

        $model = $this->findModel($id);
        if($model->status!='Rented'){
            return $this->redirect(['index']);

        }
        $model->scenario = 'uploadmoveout';
        if ($model->load(Yii::$app->request->post())) {
            $model->moveout = \yii\web\UploadedFile::getInstance($model, 'moveout');
            if($model->validate()) {
                $newFileName1 = \Yii::$app->security
                        ->generateRandomString().'.'.$model->moveout->extension;
                $model->moveout_document = $newFileName1;
                $model->moveout_date = date('Y-m-d',strtotime($model->moveout_date));
                $model->updated_at = date('Y-m-d H:i:s');
                if($model->save()){
                    $model->moveout->saveAs('uploads/moveinout/' . $newFileName1);
                    return $this->redirect(['index']);

                }else{
                    return $this->render('uploadmoveout', [
                        'model' => $model,
                    ]);
                }
            }else{
                return $this->render('uploadmoveout', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('uploadmoveout', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Deletes an existing BookingRequests model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BookingRequests model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BookingRequests the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BookingRequests::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
