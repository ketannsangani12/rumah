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
class DefectreportsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','delete','update','view','createquote','updatequote','updatestatus'],
                'rules' => [
                    [
                        'actions' => ['index','delete','create','update','view','createquote','updatequote','updatestatus'],
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
        $type = "Defect Report";
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$type);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUploadquote($id)
    {
        $model = TodoList::find()->where(['id'=>$id])->one();
        $model->scenario = 'uploadquote';
        if ($model->load(Yii::$app->request->post())) {
            $model->quote = \yii\web\UploadedFile::getInstance($model, 'quote');
            if($model->validate()) {
                $newFileName = \Yii::$app->security
                        ->generateRandomString().'.'.$model->quote->extension;
                $model->document = $newFileName;
                if($model->status=='New'){
                    $model->status = 'Pending';
                }
                $model->updated_by = Yii::$app->user->id;
                $model->updated_at = date('Y-m-d H:i:s');
                if($model->save()){
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

    /**
     * Creates a new RenovationQuotes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatequote($id)
    {
        //$model = $this->findModel($id);
//        if($model->status!='Approved'){
//            return $this->redirect(['index']);
//        }
        $modelCustomer = TodoList::findOne($id);
        $modelCustomer->scenario = 'adddefectquote';
        $modelsAddress = [new TodoItems()];

        //   [$modelAddress->scenario = 'defectquote'];
        if (!empty($_POST)) {
           //echo "<pre>";print_r($_POST);exit;
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
                    //$modelCustomer->pay_from = $_POST['TodoList']['pay_from'];
                    $modelCustomer->status = "Unpaid";
                    $modelCustomer->updated_by = Yii::$app->user->id;
                    $modelCustomer->updated_at = date('Y-m-d H:i:s');
                    if ($flag = $modelCustomer->save(false)){
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

    public function actionUpdatequote($id)
    {


        $modelCustomer = TodoList::find()->where(['id'=>$id])->one();
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
                    $modelCustomer->status = "Unpaid";
                    $modelCustomer->updated_at = date('Y-m-d H:i:s');
                    if ($flag = $modelCustomer->save(false)) {
                        if (! empty($deletedIDs)) {
                            TodoItems::deleteAll(['id' => $deletedIDs]);
                        }
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

    public function actionUpdatestatus($id)
    {
        $model = TodoList::find()->where(['id'=>$id])->one();;
        if(($model->status!='In Progress' )){
            throw new NotFoundHttpException('The requested page does not exist.');

        }
            $model->scenario = 'changestatus';

        if ($model->load(Yii::$app->request->post()) ) {

            // $model->picture = \yii\web\UploadedFile::getInstance($model, 'picture');
            if($model->validate()) {
                $model->updated_by = Yii::$app->user->id;

                if($model->save(false)) {
                    return $this->redirect(['index']);
                }

            }else{
                return $this->render('updatestatus', [
                    'model' => $model
                ]);
            }
        }else {
            return $this->render('updatestatus', [
                'model' => $model
            ]);

        }
    }

    public function actionView($id)
    {
        $query = TodoItems::find()->where(['todo_id'=>$id]);
        $query2 = TodoDocuments::find()->where(['todo_id'=>$id]);
        $model = TodoList::find()->where(['id'=>$id,'reftype'=>'Defect Report'])->one();
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
        if (($model = RenovationQuotes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
