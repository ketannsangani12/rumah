<?php

namespace app\controllers;

use app\models\TodoList;
use app\models\Users;
use Yii;
use app\models\ManualKyc;
use app\models\ManualKycSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ManualkycController implements the CRUD actions for ManualKyc model.
 */
class ManualkycController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','view','update'],
                'rules' => [
                    [
                        'actions' => ['index','view','create','update'],
                        'allow' => true,
                        'roles' => ['@'],
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

    /**
     * Lists all ManualKyc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ManualKycSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ManualKyc model.
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
     * Creates a new ManualKyc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ManualKyc();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ManualKyc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $model = $this->findModel($id);
        $model->scenario = 'updatestatus';
        if ($model->load(Yii::$app->request->post())) {
            $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
            if($model->validate()) {
                $newFileName = \Yii::$app->security
                        ->generateRandomString().'.'.$model->file->extension;
                $model->pdf = $newFileName;
                if($model->status=='Approved'){

                    $model->updated_at = date('Y-m-d H:i:s');
                    if($model->save(false)){
                        $model->file->saveAs('uploads/creditscorereports/' . $newFileName);

                        $usermodel = Users::findOne($model->user_id);
                        $usermodel->full_name = $model->full_name;
                        $usermodel->document_no = $model->document_no;
                        $usermodel->document_type = $model->type;
                        $usermodel->ekyc_document = $model->document;
                        $usermodel->ekyc_document_back = ($model->document_back!='')?$model->document_back:NULL;
                        $usermodel->document_front = $model->document;
                        $usermodel->document_back = ($model->document_back!='')?$model->document_back:NULL;
                        $usermodel->ekyc_response = 'Manual approval';
                        $usermodel->identity_status = 'Verified';
                        $usermodel->save(false);
                        return $this->redirect(['index']);
                    }else{
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }

                }else if($model->status=='Rejected'){

                    $model->updated_at = date('Y-m-d H:i:s');
                    if($model->save(false)){
                        $model->file->saveAs('uploads/creditscorereports/' . $newFileName);
                        $model->request->status = 'Cancelled';
                        $model->request->updated_at = date('Y-m-d H:i:s');
                        $model->request->save(false);
                        $todomodel = TodoList::find()->where(['request_id'=>$model->request_id])->one();
                        if(!empty($todomodel)){
                            $todomodel->remarks = 'Cancelled due to unsuccessfull Manual Kyc';
                            $todomodel->status = 'Cancelled';
                            $todomodel->updated_at = date('Y-m-d H:i:s');
                            $todomodel->save(false);
                        }
                        return $this->redirect(['index']);

                    }else{
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }
                }

            }else{
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ManualKyc model.
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
     * Finds the ManualKyc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ManualKyc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ManualKyc::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
