<?php

namespace app\controllers;

use app\models\GoldTransactions;
use app\models\Properties;
use app\models\TodoDocuments;
use app\models\TodoItems;
use app\models\TodoList;
use app\models\TodoListSearch;
use app\models\Users;
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
class AutorentalcollectionsController extends Controller
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
        $daterange = (isset($_POST['daterange']))?$_POST['daterange']:'';
        $model = new TodoList();
        $searchModel = new TodoListSearch();
        $type = "Monthly Rental";
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$type,$daterange);

        return $this->render('index', [
            'daterange'=>$daterange,
            'model'=>$model,
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

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            // $model->picture = \yii\web\UploadedFile::getInstance($model, 'picture');
            if($model->validate()) {

                $model->updated_at = date('Y-m-d h:i:s');
                if($model->save()) {
                    if ($model->status=='Paid'){
                        $goldcoins = 188;
                        $usercoinsbalance1 = Users::getcoinsbalance($model->user_id);
                        $goldtransaction = new GoldTransactions();
                        $goldtransaction->user_id = $model->user_id;
                        $goldtransaction->autorental_id = $model->id;
                        $goldtransaction->gold_coins = $goldcoins;
                        $goldtransaction->olduserbalance = $usercoinsbalance1;
                        $goldtransaction->newuserbalance = $usercoinsbalance1 + $goldcoins;
                        $goldtransaction->incoming = 1;
                        $goldtransaction->reftype = 'Rental On Time';
                        $goldtransaction->status = 'Completed';
                        $goldtransaction->created_at = date('Y-m-d H:i:s');
                        if ($goldtransaction->save(false)){
                            $update = Users::updatecoinsbalance($usercoinsbalance1 + $goldcoins, $model->user_id);

                            if ($update) {
                                return $this->redirect(['index']);
                            } else {
                                return $this->render('update', [
                                    'model' => $model
                                ]);                            }
                        } else {
                            return $this->render('update', [
                                'model' => $model
                            ]);                        }
                    }
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


    public function actionUploadcovernote($id)
    {


        $model = TodoList::find()->where(['id'=>$id,'reftype'=>'Insurance'])->one();
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
        return $this->render('view', [
            'model' => $this->findModel($id),
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
        if (($model = TodoList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
