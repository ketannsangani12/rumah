<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\UsersSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    /**
     * {@inheritdoc}
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
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        $model->scenario = 'adduser';

        if ($model->load(Yii::$app->request->post()) ) {
            // $model->picture = \yii\web\UploadedFile::getInstance($model, 'picture');
            if($model->validate()) {
                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $password = substr(str_shuffle($chars),0,6);
                $password = 123456;
                $model->password = md5($password);
                $model->status = 1;
                $model->created_at = date('Y-m-d h:i:s');
                if($model->save()){
                    Yii::$app->mailer->compose('adduser',
                        ['password'=>$password]) // a view rendering result becomes the message body here
                    ->setFrom('tlssocietyapps@gmail.com')
                        ->setTo($model->email)
                        ->setSubject('Account Created')
                        ->send();
                    return $this->redirect(['index']);

                }


            }else{
                return $this->render('create', [
                    'model' => $model
                ]);
            }
        }else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) ) {
            // $model->picture = \yii\web\UploadedFile::getInstance($model, 'picture');
            if($model->validate()) {
                $model->updated_at = date('Y-m-d h:i:s');
                if($model->save()) {
                    return $this->redirect(['index']);
                }
            }else{
                return $this->render('update', [
                    'model' => $model
                ]);
            }

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = 3;
        $model->save(false);
        // $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionUnsuspend($id)
    {
        $model = $this->findModel($id);
        $model->status = 1;
        $model->save(false);
        // $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
