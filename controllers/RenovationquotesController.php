<?php

namespace app\controllers;

use app\models\Properties;
use app\models\TodoDocuments;
use app\models\TodoItems;
use app\models\TodoList;
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
class RenovationquotesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','delete','update','view','milestones','createmilestone','updatemilestone','viewmilestone','uploadmilestonedocument'],
                'rules' => [
                    [
                        'actions' => ['index','delete','create','update','view','milestones','createmilestone','updatemilestone','viewmilestone','uploadmilestonedocument'],
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
        $searchModel = new RenovationQuotesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RenovationQuotes model.
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
     * Creates a new RenovationQuotes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RenovationQuotes();
        $model->scenario = 'addquote';
        if ($model->load(Yii::$app->request->post())) {
            $model->document = \yii\web\UploadedFile::getInstance($model, 'document');
//renovationquotes
            if($model->validate()) {
                $propertyxist = Properties::findOne($model->property_id);

                $newFileName = \Yii::$app->security
                        ->generateRandomString().'.'.$model->document->extension;
                $model->landlord_id = $propertyxist->user_id;

                $model->quote_document = $newFileName;
                $model->status = "Pending";
                $model->created_at = date('Y-m-d H:i:s');
                if($model->save()){
                    $model->document->saveAs('uploads/renovationquotes/' . $newFileName);
                    $todorequest = new TodoList();
                    $todorequest->renovation_quote_id = $model->id;
                    $todorequest->property_id = $model->property_id;
                    $todorequest->reftype = 'Renovation Quote';
                    $todorequest->status = 'Pending';
                    $todorequest->created_at = date("Y-m-d H:i:s");
                    $todorequest->save(false);
                    return $this->redirect(['index']);
                }

            }else{
                return $this->render('create', [
                    'model' => $model,
                ]);
            }        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RenovationQuotes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMilestones($id)
    {
        $model = $this->findModel($id);
        if($model->status=='Pending' || $model->status=='Rejected'){
            return $this->redirect(['index']);
        }
        $query = TodoList::find()->where(['renovation_quote_id'=>$id,'reftype'=>'Renovation Milestone']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        //$milestones = ;

            return $this->render('milestones', [
                'model' => $model,
                'dataProvider' => $dataProvider,

            ]);

    }
    public function actionCreatemilestone($id)
    {

        $model = $this->findModel($id);
//        if($model->status!='Approved'){
//            return $this->redirect(['index']);
//        }
        $modelCustomer = new TodoList();
        $modelCustomer->scenario = 'addmilestone';
        $modelsAddress = [new TodoItems()];
        if (!empty($_POST)) {
            $modelCustomer->load(Yii::$app->request->post());
            //$modelCustomer->
            $modelsAddress = Model::createMultiple(TodoItems::classname());
            Model::loadMultiple($modelsAddress, Yii::$app->request->post());
            $propertyxist = Properties::findOne($model->property_id);

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
                    $modelCustomer->property_id = $model->property_id;
                    $modelCustomer->renovation_quote_id = $id;
                    $modelCustomer->landlord_id = $model->landlord_id;
                    $modelCustomer->reftype = "Renovation Milestone";
                    $modelCustomer->status = "Unpaid";
                    $modelCustomer->created_at = date('Y-m-d H:i:s');
                    if ($flag = $modelCustomer->save(false)) {
                        $model->status = 'Work In Progress';
                        $model->save(false);
                        $total = 0;
                        foreach ($modelsAddress as $modelAddress) {
                            $total+=$modelAddress->price+$modelAddress->platform_deductible;
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
                        return $this->redirect(['milestones','id'=>$id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }else {
            return $this->render('addmilestone', [
                'model' => $model,
                'modelCustomer' => $modelCustomer,
                'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress
            ]);
        }

    }

    public function actionUpdatemilestone($id)
    {

        $modelCustomer = TodoList::findOne($id);

        $model = $this->findModel($modelCustomer->renovation_quote_id);
//        if($model->status!='Approved'){
//            return $this->redirect(['index']);
//        }
        $modelCustomer->scenario = 'addmilestone';
        $modelsAddress = $modelCustomer->todoItems;

        //$modelsAddress = [new TodoItems()];
        if (!empty($_POST)) {
            $modelCustomer->load(Yii::$app->request->post());

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
                        return $this->redirect(['milestones','id'=>$modelCustomer->renovation_quote_id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }else {
            return $this->render('addmilestone', [
                'model' => $model,
                'modelCustomer' => $modelCustomer,
                'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress
            ]);
        }

    }



    public function actionViewmilestone($id)
    {
        $query = TodoItems::find()->where(['todo_id'=>$id]);
        $query2 = TodoDocuments::find()->where(['todo_id'=>$id]);
        $model = TodoList::find()->where(['id'=>$id,'reftype'=>'Renovation Milestone'])->one();
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

        return $this->render('viewmilestone', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,

        ]);

    }


    public function actionUploadmilestonedocument($id)
    {

        $modelCustomer = TodoList::findOne($id);

        $model = $this->findModel($modelCustomer->renovation_quote_id);
        //$modelCustomer = new TodoList();
        //$modelCustomer->scenario = 'uploadmilestonedocument';
        $modelsAddress = [new TodoDocuments()];
        if (!empty($_POST)) {
            $modelCustomer->load(Yii::$app->request->post());
            //$modelCustomer->
            $modelsAddress = Model::createMultiple(TodoDocuments::classname());
            Model::loadMultiple($modelsAddress, Yii::$app->request->post());
            foreach ($modelsAddress as $index => $modelOptionValue) {
                //echo "<pre>";print_r(\yii\web\UploadedFile::getInstance($modelOptionValue, "[{$index}]document_pdf"));exit;
                $modelOptionValue->document_pdf = \yii\web\UploadedFile::getInstance($modelOptionValue, "[{$index}]document_pdf");
            }
            $propertyxist = Properties::findOne($model->property_id);

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
                    //if ($flag = $modelCustomer->save(false)) {
                        foreach ($modelsAddress as $modelAddress) {
                            $newFileName = \Yii::$app->security
                                    ->generateRandomString().'.'.$modelAddress->document_pdf->extension;
                            $modelAddress->document = $newFileName;
                            $modelAddress->todo_id = $modelCustomer->id;
                            $modelAddress->created_at = date('Y-m-d H:i:s');

                            if (! ($flag = $modelAddress->save(false))) {

                                $transaction->rollBack();
                                break;
                            }
                        }
                    //}
                    if ($flag) {
                        foreach ($modelsAddress as $modelAddress) {
                            $modelAddress->document_pdf->saveAs('uploads/tododocuments/' . $modelAddress->document);

                        }
                        $transaction->commit();
                        return $this->redirect(['milestones','id'=>$modelCustomer->renovation_quote_id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }else{
            return $this->render('uploadmilestonedocument', [
                'model' => $model,
                'modelCustomer' => $modelCustomer,
                'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress
            ]);
        }

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
        if (($model = RenovationQuotes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
