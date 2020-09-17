<?php

namespace app\controllers;

use Yii;
use app\models\Ilifestyle;
use app\models\IlifestyleSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IlifestyleController implements the CRUD actions for Ilifestyle model.
 */
class IlifestyleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','view','update','create'],
                'rules' => [
                    [
                        'actions' => ['index','view','update','create'],
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
     * Lists all Ilifestyle models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IlifestyleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ilifestyle model.
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
     * Creates a new Ilifestyle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ilifestyle();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post())) {
            $model->picture = \yii\web\UploadedFile::getInstance($model, 'picture');
            if($model->validate()) {
                $newFileName = \Yii::$app->security
                        ->generateRandomString().'.'.$model->picture->extension;

                $model->image = "uploads/articles/".$newFileName;
                $model->created_at = date('Y-m-d');
                if($model->save()) {
                    $model->picture->saveAs('uploads/articles/' . $newFileName);

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
     * Updates an existing Ilifestyle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()) {
                if(!empty($_FILES['Ilifestyle']['name']['picture'])){
                    $model->picture = \yii\web\UploadedFile::getInstance($model, 'picture');
                    $newFileName = \Yii::$app->security
                            ->generateRandomString().'.'.$model->picture->extension;
                    $model->image = "uploads/articles/".$newFileName;
                }
                $model->created_at = date('Y-m-d');
                if($model->save()) {
                    if(!empty($_FILES['Ilifestyle']['name']['picture'])) {
                        $model->picture->saveAs('uploads/articles/' . $newFileName);
                    }

                    return $this->redirect(['index']);
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
     * Deletes an existing Ilifestyle model.
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
     * Finds the Ilifestyle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ilifestyle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ilifestyle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
