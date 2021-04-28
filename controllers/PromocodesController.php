<?php

namespace app\controllers;

use Yii;
use app\models\PromoCodes;
use app\models\PromocodesSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PromocodesController implements the CRUD actions for PromoCodes model.
 */
class PromocodesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','delete','update','view'],
                'rules' => [
                    [
                        'actions' => ['index','delete','create','update','view'],
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
     * Lists all PromoCodes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PromocodesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PromoCodes model.
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
     * Creates a new PromoCodes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $model = new PromoCodes();
        $model->scenario = 'addpromocode';
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                $model->expiry_date = date('Y-m-d',strtotime($model->expiry_date));
                $model->created_at = date('Y-m-d H:i:s');
                if($model->save()){
                    return $this->redirect(['index']);

                }
            }else{
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PromoCodes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $model = $this->findModel($id);
        $model->scenario = 'addpromocode';

        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                $model->expiry_date = date('Y-m-d',strtotime($model->expiry_date));
                $model->updated_at = date('Y-m-d H:i:s');
                if($model->save()){
                    return $this->redirect(['index']);

                }

            }else{
                $model->expiry_date = date('d-m-Y',strtotime($model->expiry_date));

                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            $model->expiry_date = date('d-m-Y',strtotime($model->expiry_date));

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PromoCodes model.
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
     * Finds the PromoCodes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PromoCodes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PromoCodes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
