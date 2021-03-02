<?php

namespace app\controllers;

use app\models\Topups;
use app\models\Users;
use Complex\Exception;
use Yii;
use app\models\Transactions;
use app\models\TransactionsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransactionsController implements the CRUD actions for Transactions model.
 */
class TransactionsController extends Controller
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
     * Lists all Transactions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transactions model.
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
     * Creates a new Transactions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = new Transactions();
            $model->scenario = 'createtopup';
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $userbalance = Users::getbalance($model->user_id);
                    $topupmodel = new Topups();
                    $topupmodel->user_id = $model->user_id;
                    $topupmodel->amount = $model->amount;
                    $topupmodel->oldbalance = $userbalance;
                    $topupmodel->newbalance = $userbalance + $model->amount;
                    $topupmodel->created_at = date('Y-m-d H:i:s');
                    if ($topupmodel->save(false)) {
                        $transactionmodel = new Transactions();
                        $transactionmodel->user_id = $model->user_id;
                        $transactionmodel->amount = $model->amount;
                        $transactionmodel->total_amount = $model->amount;
                        $transactionmodel->topup_id = $topupmodel->id;
                        $transactionmodel->type = 'Payment';
                        $transactionmodel->reftype = 'Topup';
                        $transactionmodel->status = 'Completed';
                        $transactionmodel->created_at = date('Y-m-d H:i:s');
                        $transactionmodel->updated_at = date('Y-m-d H:i:s');
                        $transactionmodel->updated_by = Yii::$app->user->id;
                        if ($transactionmodel->save(false)) {
                            $lastid = $transactionmodel->id;
                            $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                            $transactionmodel->reference_no = $reference_no;
                            $transactionmodel->save(false);
                            $updatesenderbalance = Users::updatebalance($topupmodel->newbalance, $model->user_id);
                            $transaction->commit();

                            return $this->redirect(['index']);

                        } else {
                            $transaction->rollBack();
                            return $this->render('create', [
                                'model' => $model,
                            ]);

                        }


                    } else {
                        $transaction->rollBack();

                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }


                } else {

                    $transaction->rollBack();

                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {

                $transaction->rollBack();

                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }catch (Exception $e) {
            // # if error occurs then rollback all transactions
            $transaction->rollBack();
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Transactions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
                if($model->validate()) {
                    return $this->redirect(['index']);

                }else{

                }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Transactions model.
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
     * Finds the Transactions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transactions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transactions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
