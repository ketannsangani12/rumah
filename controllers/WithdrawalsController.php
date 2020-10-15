<?php

namespace app\controllers;

use app\models\Transactions;
use app\models\Users;
use Yii;
use app\models\Withdrawals;
use app\models\WithdrawalsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WithdrawalsController implements the CRUD actions for Withdrawals model.
 */
class WithdrawalsController extends Controller
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
     * Lists all Withdrawals models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WithdrawalsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Withdrawals model.
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
     * Creates a new Withdrawals model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Withdrawals();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Withdrawals model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'updatewithdrawal';
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if($model->status=='Completed'){
                    $model->save(false);
                    $transactionmodel = Transactions::findOne(['withdrawal_id' => $id]);
                    $transactionmodel->status = 'Declined';
                    $transactionmodel->save(false);
                    return $this->redirect(['index']);

                }else if($model->status=='Declined'){
                    $model->save(false);
                    $transactionmodel = Transactions::findOne(['withdrawal_id' => $id]);
                    $transactionmodel->status = 'Declined';
                    if ($transactionmodel->save()) {
                            $userbalance = Users::getbalance($model->user_id);
                            $model->old_balance = $userbalance;
                            $model->new_balance = $userbalance + $model->amount;
                            if ($model->save(false)) {
                                Users::updatebalance($model->new_balance,$model->user_id);

                            }

                        return $this->redirect(['index']);


                    }else{
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }

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
     * Deletes an existing Withdrawals model.
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
     * Finds the Withdrawals model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Withdrawals the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Withdrawals::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
