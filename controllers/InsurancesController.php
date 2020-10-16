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
class InsurancesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','delete','update','view','uploadcovernote'],
                'rules' => [
                    [
                        'actions' => ['index','delete','create','update','view','uploadcovernote'],
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
        $type = "Insurance";
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
        $modelCustomer->scenario = 'addinsurance';
        $modelsAddress = [new TodoItems()];
        if (!empty($_POST)) {
            $modelCustomer->load(Yii::$app->request->post());
            //$modelCustomer->
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
                    $modelCustomer->landlord_id = $propertyxist->user_id;
                    $modelCustomer->reftype = "Insurance";
                    $modelCustomer->status = "Unpaid";
                    $modelCustomer->created_at = date('Y-m-d H:i:s');
                    $modelCustomer->updated_at = date('Y-m-d H:i:s');

                    if ($flag = $modelCustomer->save(false)) {
                        $total = 0;
                        foreach ($modelsAddress as $modelAddress) {
                            $total+=$modelAddress->price;
                            $modelAddress->todo_id = $modelCustomer->id;
                            $modelAddress->created_at = date('Y-m-d H:i:s');
                            if (! ($flag = $modelAddress->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                        $sst = Yii::$app->common->calculatesst($total);
                        $grandtotal = $total+$sst;
                        $modelCustomer->subtotal = $total;
                        $modelCustomer->sst = $sst;
                        $modelCustomer->total = $grandtotal;
                        $modelCustomer->save(false);

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

    /**
     * Updates an existing RenovationQuotes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    public function actionUpdate($id)
    {


        $modelCustomer = TodoList::find()->where(['id'=>$id,'reftype'=>'Insurance','status'=>'Unpaid'])->one();
        if ($modelCustomer == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $modelsAddress = $modelCustomer->todoItems;

        if (!empty($_POST)) {

            $oldIDs = ArrayHelper::map($modelsAddress, 'id', 'id');
            $modelsAddress = Model::createMultiple(TodoItems::classname(), $modelsAddress);
            Model::loadMultiple($modelsAddress, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsAddress, 'id', 'id')));

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
                    if ($flag = $modelCustomer->save(false)) {
                        if (! empty($deletedIDs)) {
                            TodoItems::deleteAll(['id' => $deletedIDs]);
                        }
                        $total = 0;
                        foreach ($modelsAddress as $modelAddress) {
                            $total+=$modelAddress->price;
                            $modelAddress->todo_id = $modelCustomer->id;
                            $modelAddress->created_at = date('Y-m-d H:i:s');
                            if (! ($flag = $modelAddress->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                        $sst = Yii::$app->common->calculatesst($total);
                        $grandtotal = $total+$sst;
                        $modelCustomer->subtotal = $total;
                        $modelCustomer->sst = $sst;
                        $modelCustomer->total = $grandtotal;
                        $modelCustomer->save(false);

                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }else {
            return $this->render('update', [
                //'model' => $model,
                'modelCustomer' => $modelCustomer,
                'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress
            ]);
        }

    }


    public function actionUploadcovernote($id)
    {


        $model = $this->findModel($id);
        $modeldocument1 = TodoDocuments::find()->where(['todo_id'=>$id])->one();
        $modeldocument = (!empty($modeldocument1))?$modeldocument1:new TodoDocuments();
        if ($modeldocument->load(Yii::$app->request->post())) {
            $modeldocument->description = 'Insurance Cover Note';
            $modeldocument->document_pdf = \yii\web\UploadedFile::getInstance($modeldocument, 'document_pdf');
            if($model->validate()) {
                $newFileName1 = \Yii::$app->security
                        ->generateRandomString().'.'.$modeldocument->document_pdf->extension;
                $modeldocument->todo_id = $id;
                $modeldocument->document = $newFileName1;
                $modeldocument->created_at = date('Y-m-d H:i:s');
                if($modeldocument->save()){
                    $model->status = "Completed";
                    $model->updated_at = date('Y-m-d H:i:s');
                    $model->save(false);
                    $modeldocument->document_pdf->saveAs('uploads/tododocuments/' . $newFileName1);
                    return $this->redirect(['index']);

                }else{
                    return $this->render('uploadcovernote', [
                        'model' => $model,
                        'modeldocument'=>$modeldocument
                    ]);
                }
            }else{
                return $this->render('uploadcovernote', [
                    'model' => $model,
                    'modeldocument'=>$modeldocument
                ]);
            }
        }else {
            return $this->render('uploadcovernote', [
                //'model' => $model,
                'model' => $model,
                'modeldocument'=>$modeldocument
              //  'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress
            ]);
        }

    }

    public function actionView($id)
    {
        $query = TodoItems::find()->where(['todo_id'=>$id]);
        $query2 = TodoDocuments::find()->where(['todo_id'=>$id]);
        $model = $this->findModel($id);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $dataProvider2 = new ActiveDataProvider([
            'query' => $query2,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        //$milestones = ;

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,

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
        if (($model = TodoList::find()->where(['id'=>$id,'reftype'=>'Insurance'])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
