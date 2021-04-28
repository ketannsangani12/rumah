<?php

namespace app\controllers;

use Yii;
use app\models\Properties;
use app\models\PropertiesSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PropertiesController implements the CRUD actions for Properties model.
 */
class ManagedpropertiesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','delete','update'],
                'rules' => [
                    [
                        'actions' => ['index','delete','create','update'],
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
     * Lists all Properties models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PropertiesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'managedlisting'=>true
        ]);
    }


    public function actionManagedlisting()
    {
        $searchModel = new PropertiesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'managedlisting'=>true
        ]);
    }
    /**
     * Displays a single Properties model.
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
     * Creates a new Properties model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $model = new Properties();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post())) {
            $model->amenities = implode(',',$_POST['Properties']['amenities']);
            $model->commute = implode(',',$_POST['Properties']['commute']);
            if($model->validate()) {
                $model->availability = date('Y-m-d',strtotime($model->availability));
                $model->created_at = date('Y-m-d');
                if($model->save()) {
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


    public function actionAdd()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $model = new Properties();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post())) {
            $model->amenities = implode(',',$_POST['Properties']['amenities']);
            $model->commute = implode(',',$_POST['Properties']['commute']);
            if($model->validate()) {
                $model->availability = date('Y-m-d',strtotime($model->availability));
                $model->created_at = date('Y-m-d');
                if($model->save()) {
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
     * Updates an existing Properties model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->amenities = implode(',',$_POST['Properties']['amenities']);
            $model->commute = implode(',',$_POST['Properties']['commute']);
            if($model->validate()) {
                $model->availability = date('Y-m-d',strtotime($model->availability));
                $model->created_at = date('Y-m-d');
                if($model->save()) {
                    return $this->redirect(['index']);
                }

            }else{
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            $model->availability = date('d-m-Y',strtotime($model->availability));
            $model->amenities = explode(',',$model->amenities);
            $model->commute = explode(',',$model->commute);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Properties model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $model = $this->findModel($id);
        $model->status = 'Deleted';
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->save(false);

        return $this->redirect(['index']);
    }

    public function actionRemovefrommanagelisting($id)
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $model = $this->findModel($id);

        $model->is_managed = 0;
        $model->pe_userid = Yii::$app->user->identity->getId();
        $model->save(false);
        return $this->redirect(['index']);
    }
    /**
     * Finds the Properties model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Properties the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Properties::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
