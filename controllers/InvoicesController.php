<?php

namespace app\controllers;

use app\models\Properties;
use app\models\TodoDocuments;
use app\models\TodoItems;
use app\models\TodoList;
use app\models\TodoListSearch;
use Yii;
use app\models\RenovationQuotes;
use app\models\RenovationQuotesSearch;
use yii\base\Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

/**
 * RenovationquotesController implements the CRUD actions for RenovationQuotes model.
 */
class InvoicesController extends Controller
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
     * Lists all RenovationQuotes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TodoListSearch();
        $type = "General";
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$type);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * Creates a new RenovationQuotes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //$model = $this->findModel($id);
//        if($model->status!='Approved'){
//            return $this->redirect(['index']);
//        }
        $modelCustomer = new TodoList();
        $modelCustomer->scenario = 'addinvoice';
        $modelsAddress = [new TodoItems()];

        //   [$modelAddress->scenario = 'defectquote'];
        if ($modelCustomer->load(Yii::$app->request->post())) {

            $modelsAddress = Model::createMultiple(TodoItems::classname());
            Model::loadMultiple($modelsAddress, Yii::$app->request->post());
            $propertyxist = Properties::findOne($modelCustomer->property_id);

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsAddress),
                    ActiveForm::validate($modelCustomer)
                );
            }

            // validate all models
            $valid = $modelCustomer->validate();
            $valid = Model::validateMultiple($modelsAddress) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($modelCustomer->pay_from=='Tenant' && !isset($propertyxist->request->user_id)){
                        Yii::$app->session->setFlash('error', "This property is not rented");
                        return $this->redirect(['create']);

                    }
                    //$modelCustomer->pay_from = $_POST['TodoList']['pay_from'];
                    $modelCustomer->request_id = $propertyxist->request_id;
                    $modelCustomer->user_id = ($modelCustomer->pay_from=='Tenant')?$propertyxist->request->user_id:NULL;
                    $modelCustomer->landlord_id = ($modelCustomer->pay_from=='Landlord')?$propertyxist->user_id:NULL;
                    $modelCustomer->due_date = date('Y-m-d',strtotime($modelCustomer->due_date));
                    $modelCustomer->reftype = 'General';
                    $modelCustomer->status = "Unpaid";
                    $modelCustomer->created_at = date('Y-m-d H:i:s');
                    $modelCustomer->updated_at = date('Y-m-d H:i:s');
                    if ($flag = $modelCustomer->save(false)){
                        foreach ($modelsAddress as $modelAddress) {
                            $modelAddress->todo_id = $modelCustomer->id;
                            $modelAddress->created_at = date('Y-m-d H:i:s');
                            if (! ($flag = $modelAddress->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }else{
                return $this->render('create', [
                    'modelCustomer' => $modelCustomer,
                    'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress
                ]);
            }
        } else {
            return $this->render('create', [
                'modelCustomer' => $modelCustomer,
                'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress
            ]);
        }
    }

    public function actionUpdate($id)
    {


        $modelCustomer = TodoList::find()->where(['id'=>$id,'reftype'=>'General','status'=>'Unpaid'])->one();
        if ($modelCustomer == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $modelsAddress = $modelCustomer->todoItems;
        $modelCustomer->scenario = 'addinvoice';

        if ($modelCustomer->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelsAddress, 'id', 'id');
            $modelsAddress = Model::createMultiple(TodoItems::classname(), $modelsAddress);
            Model::loadMultiple($modelsAddress, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsAddress, 'id', 'id')));
            $propertyxist = Properties::findOne($modelCustomer->property_id);

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsAddress),
                    ActiveForm::validate($modelCustomer)
                );
            }

            // validate all models
            $valid = $modelCustomer->validate();
            $valid = Model::validateMultiple($modelsAddress) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    //echo "<pre>"; print_r($modelCustomer->getAttributes());exit;
                    $modelCustomer->user_id = ($modelCustomer->pay_from=='Tenant')?$propertyxist->request->user_id:NULL;
                    $modelCustomer->landlord_id = ($modelCustomer->pay_from=='Landlord')?$propertyxist->request->landlord_id:NULL;
                    $modelCustomer->due_date = date('Y-m-d',strtotime($modelCustomer->due_date));
                    if ($flag = $modelCustomer->save(false)) {
                        if (! empty($deletedIDs)) {
                            TodoItems::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsAddress as $modelAddress) {
                            $modelAddress->todo_id = $modelCustomer->id;
                            $modelAddress->created_at = date('Y-m-d H:i:s');
                            if (! ($flag = $modelAddress->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }else{
                return $this->render('update', [
                    //'model' => $model,
                    'modelCustomer' => $modelCustomer,
                    'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress
                ]);
                //print_r($modelCustomer->getErrors());exit;
            }
        } else {
            return $this->render('update', [
                //'model' => $model,
                'modelCustomer' => $modelCustomer,
                'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress
            ]);
        }

    }

    /**
     * Updates an existing RenovationQuotes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */



    public function actionView($id)
    {
        $query = TodoItems::find()->where(['todo_id'=>$id]);
        //$query2 = TodoDocuments::find()->where(['todo_id'=>$id]);
        $model = $this->findModel($id);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        //$milestones = ;

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            //'dataProvider2' => $dataProvider2,

        ]);

    }

    /**
     * Deletes an existing RenovationQuotes model.
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
     * Finds the RenovationQuotes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RenovationQuotes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TodoList::find()->where(['id'=>$id,'reftype'=>'General'])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
