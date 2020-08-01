<?php

namespace app\controllers;

use app\models\ServicerequestImages;
use app\models\TodoItems;
use app\models\TodoList;
use Yii;
use app\models\ServiceRequests;
use app\models\ServiceRequestsSearch;
use yii\base\Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

/**
 * ServicerequestsController implements the CRUD actions for ServiceRequests model.
 */
class ServicerequestsController extends Controller
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
     * Lists all ServiceRequests models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServiceRequestsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ServiceRequests model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $query = TodoItems::find()->where(['todo_id'=>$model->todo_id,'reftype'=>'Other']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $query1 = TodoItems::find()->where(['todo_id'=>$model->todo_id,'reftype'=>'Refund']);
        $dataProvider1 = new ActiveDataProvider([
            'query' => $query1,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        return $this->render('view', [
            'model' => $model,
            'dataProvider'=>$dataProvider,
            'dataProvider1'=>$dataProvider1
        ]);
    }

    public function actionUploadquote($id)
    {
        $model = $this->findModel($id);
        $todoexist = TodoList::find()->where(['service_request_id'=>$id,'reftype'=>'Service'])->one();
        if($todoexist==null){
            throw new NotFoundHttpException('The requested page does not exist.');

        }
        $model->scenario = 'uploadquote';
        if ($model->load(Yii::$app->request->post())) {
            $model->quote = \yii\web\UploadedFile::getInstance($model, 'quote');
            if($model->validate()) {

                $newFileName = \Yii::$app->security
                        ->generateRandomString().'.'.$model->quote->extension;

                $todoexist->document = $newFileName;
                $todoexist->updated_at = date('Y-m-d H:i:s');
                $todoexist->status = "Pending";
                $model->status = "Pending";
                $model->updated_at = date('Y-m-d H:i:s');
                if($todoexist->save()){
                    $model->save();
                    $model->quote->saveAs('uploads/tododocuments/' . $newFileName);
                    return $this->redirect(['index']);

                }else{
                    return $this->render('uploadquote', [
                        'model' => $model,
                    ]);
                }
            }else{
                return $this->render('uploadquote', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('uploadquote', [
                'model' => $model,
            ]);
        }
    }
    public function actionIssueinvoice($id)
    {

        $model = $this->findModel($id);

        $modelCustomer = TodoList::findOne($model->todo_id);
        $modelsAddress = [new TodoItems()];
        if (!empty($_POST)) {

            $modelsAddress = Model::createMultiple(TodoItems::classname());
            Model::loadMultiple($modelsAddress, Yii::$app->request->post());

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
                    $model->status = "Unpaid";
                    $model->save(false);
                    $modelCustomer->status = "Unpaid";
                    $modelCustomer->created_at = date('Y-m-d H:i:s');
                    if ($flag = $modelCustomer->save(false)) {
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
            }
        }else {
            return $this->render('issueinvoice', [
                'model' => $model,
                'modelCustomer' => $modelCustomer,
                'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress,
                'type'=>'Issue Invoice'
            ]);
        }

    }

    public function actionIssueinvoiceupdate($id)
    {

        $model = $this->findModel($id);

        $modelCustomer = TodoList::find()->where(['id'=>$model->todo_id,'reftype'=>'Service'])->one();
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
            }
        }else {
            return $this->render('issueinvoice', [
                'model' => $model,
                'modelCustomer' => $modelCustomer,
                'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress,
                'type'=>'Issue Invoice'
            ]);
        }

    }

    /**
     * Creates a new ServiceRequests model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ServiceRequests();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ServiceRequests model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdatestatus($id)
    {
        $merchantmodel = $this->findModel($id);
        if(($merchantmodel->reftype=='Laundry' || $merchantmodel->reftype=='Cleaner')){
            throw new NotFoundHttpException('The requested page does not exist.');

        }
        $merchantmodel->scenario = 'changestatus';
        $model = new ServicerequestImages();

        $query = ServicerequestImages::find()->where(['service_request_id' => $id,'reftype'=>'oeuploadedphotos']);
        $images = $query->all();

        if ($merchantmodel->load(Yii::$app->request->post()) ) {
            // $model->picture = \yii\web\UploadedFile::getInstance($model, 'picture');
            if($merchantmodel->validate()) {
                if($merchantmodel->save(false)) {
                    $todolist = $merchantmodel->todo;
                    $todolist->status = $merchantmodel->status;
                    $todolist->save(false);
                    return $this->redirect(['index']);
                }

            }else{
                return $this->render('update', [
                    'model' => $model,
                    'merchantmodel' => $merchantmodel,
                    'images' => $images
                ]);
            }
        }else {
            $servicetype = $merchantmodel->reftype;
            return $this->render('update', [
                'model' => $model,
                'merchantmodel' => $merchantmodel,
                'images' => $images
            ]);

        }
    }

    public function actionRefund($id)
    {

        $model = $this->findModel($id);

        $modelCustomer = TodoList::findOne($model->todo_id);
        $modelsAddress = [new TodoItems()];
        if (!empty($_POST)) {

            $modelsAddress = Model::createMultiple(TodoItems::classname());
            Model::loadMultiple($modelsAddress, Yii::$app->request->post());

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
                    $model->status = "Refund Requested";
                    $model->save(false);
                    $modelCustomer->status = "Refund Requested";
                    $modelCustomer->created_at = date('Y-m-d H:i:s');
                    if ($flag = $modelCustomer->save(false)) {
                        foreach ($modelsAddress as $modelAddress) {
                            $modelAddress->reftype = "Refund";
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
            }
        }else {
            return $this->render('issueinvoice', [
                'type'=>'Refund',
                'model' => $model,
                'modelCustomer' => $modelCustomer,
                'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress,
                //'type'=>'moveoutinvoice'
            ]);
        }

    }

    /**
     * Deletes an existing ServiceRequests model.
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
     * Finds the ServiceRequests model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ServiceRequests the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ServiceRequests::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
