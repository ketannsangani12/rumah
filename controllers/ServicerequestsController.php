<?php

namespace app\controllers;

use app\models\Properties;
use app\models\ServicerequestImages;
use app\models\TodoItems;
use app\models\TodoList;
use app\models\Users;
use Yii;
use app\models\ServiceRequests;
use app\models\ServiceRequestsSearch;
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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','uploadquote','issueinvoice','issueinvoiceupdate','updatestatus','refund'],
                'rules' => [
                    [
                        'actions' => ['index','uploadquote','issueinvoice','issueinvoiceupdate','updatestatus','refund'],
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
        $query = TodoItems::find()->where(['todo_id'=>$model->todo_id,'reftype'=>'Payment']);
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
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $model = $this->findModel($id);
        $todoexist = TodoList::find()->where(['service_request_id'=>$id,'reftype'=>'Service','status'=>'New'])->one();
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
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $model = $this->findModel($id);

        $modelCustomer = TodoList::findOne($model->todo_id);
        if ($modelCustomer == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
        date_default_timezone_set("Asia/Kuala_Lumpur");

        $model = $this->findModel($id);

        $modelCustomer = TodoList::find()->where(['id'=>$model->todo_id,'reftype'=>'Service'])->one();
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
    public function actionCreatecleaningorder()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $model = new ServiceRequests();
        $model->scenario = 'createcleaningorder';
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $requestexist = ServiceRequests::find()->where(['property_id' => $model->property_id, 'reftype' => 'Cleaner'])->andWhere(['in', 'status', ['New', 'Pending', 'Unpaid', 'In Progress']])->one();
//                    if (!empty($requestexist)) {
//                        Yii::$app->session->setFlash('error', "Cleaner request already exist in system.");
//                        return $this->redirect(['createmoverorder']);
//
//                    }
                    if ($model->request_to == 'Tenant' && !isset($model->request->user_id)) {
                        Yii::$app->session->setFlash('error', "This property is not rented");
                        return $this->redirect(['createmoverorder']);

                    }
                    $priceperhour = 40;
                    $addonprice = (!empty($model->addons)) ? 28 : 0;
                    //print_r($addonprice);exit;
                    $model->addons = null;
                    $propertydetails = Properties::findOne($model->property_id);
                    $model->user_id = ($model->request_to == 'Tenant') ? $model->request->user_id : $propertydetails->user_id;
                    $model->request_to = null;
                    $model->date = date('Y-m-d', strtotime($model->date));
                    $model->reftype = 'Cleaner';
                    $model->status = 'New';
                    $model->created_at = date('Y-m-d H:i:s');
                    $model->booked_at = date('Y-m-d H:i:s');
                    if ($model->save(false)) {
                        $request_id = $model->id;
                        $reference_no = Yii::$app->common->generatereferencenumber($request_id);
                        $model->reference_no = $reference_no;
                        if ($model->save(false)) {
                            $todolist = new TodoList();
                            $todolist->user_id = $model->user_id;
                            $todolist->service_request_id = $request_id;
                            $todolist->property_id = $model->property_id;
                            $todolist->vendor_id = $model->vendor_id;
                            $todolist->reftype = 'Service';
                            $todolist->service_type = 'Cleaner';
                            $todolist->created_at = date("Y-m-d H:i:s");
                            $todolist->updated_at = date("Y-m-d H:i:s");
                            $todolist->status = 'New';
                            if ($todolist->save(false)) {
                                $todoitems = new TodoItems();
                                $todoitems->todo_id = $todolist->id;
                                $todoitems->description = 'Cleaning Services (' . $model->hours . ' hours)';
                                $todoitems->price = $priceperhour * $model->hours;
                                $todoitems->created_at = date("Y-m-d H:i:s");
                                $todoitems->save(false);
                                if ($addonprice > 0) {
                                    $todoitems1 = new TodoItems();
                                    $todoitems1->todo_id = $todolist->id;
                                    $todoitems1->description = 'Cleaning Tools ';
                                    $todoitems1->price = $addonprice;
                                    $todoitems1->created_at = date("Y-m-d H:i:s");
                                    $todoitems1->save(false);
                                }
                                $subtotal = ($priceperhour * $model->hours) + $addonprice;
                                $sst = Yii::$app->common->calculatesst($subtotal);
                                $total_amount = $subtotal + $sst;
                                $model->subtotal = $subtotal;
                                $model->sst = $sst;
                                $model->total_amount = $total_amount;
                                $model->todo_id = $todolist->id;
                                if ($model->save(false)) {
                                    $todolist->subtotal = $subtotal;
                                    $todolist->sst = $sst;
                                    $todolist->total = $total_amount;
                                    $todolist->save(false);
                                    $cleaner = Users::findOne($model->vendor_id);
                                    $cleaner->current_status = 'Busy';
                                    if($cleaner->save(false)){
                                        $transaction->commit();
                                        $transaction->commit();
                                        $subject = 'Service order placed';
                                        $textmessage = 'You got one service order pending for action, kindly accept now.';
                                        Yii::$app->common->Savenotification($todolist->vendor_id,$subject,$textmessage,'',$model->property_id,$todolist->id);

                                        Yii::$app->common->Sendpushnotification($todolist->vendor_id,$subject,$textmessage,'Partner');

                                        return $this->redirect(['index']);
                                    }else{
                                        $transaction->rollBack();

                                        return $this->render('create', [
                                            'model' => $model
                                        ]);
                                    }

                                }

                            } else {
                                $transaction->rollBack();

                                return $this->render('create', [
                                    'model' => $model
                                ]);
                            }
                        }
                    } else {
                        $transaction->rollBack();

                        return $this->render('create', [
                            'model' => $model
                        ]);
                    }
                } else {
                    $transaction->rollBack();

                    return $this->render('create', [
                        'model' => $model
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
    public function actionCreatemoverorder()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $model = new ServiceRequests();
        $model->scenario = 'createmoverorder';

        if ($model->load(Yii::$app->request->post()) ) {
            if($model->validate()) {
                $requestexist = ServiceRequests::find()->where(['property_id'=>$model->property_id,'reftype'=>'Mover'])->andWhere(['in','status',['New','Pending','Unpaid','Confirmed']])->one();
                if (!empty($requestexist)){
                    Yii::$app->session->setFlash('error', "Mover request already exist in system.");
                    return $this->redirect(['createmoverorder']);

                }
                if ($model->request_to=='Tenant' && !isset($model->request->user_id)){
                    Yii::$app->session->setFlash('error', "This property is not rented");
                    return $this->redirect(['createmoverorder']);

                }
                $propertydetails = Properties::findOne($model->property_id);
                $model->user_id = ($model->request_to=='Tenant')?$model->request->user_id:$propertydetails->user_id;
                $model->request_to = null;
                $model->date = date('Y-m-d',strtotime($model->date));
                $model->reftype = 'Mover';
                $model->status = 'New';
                $model->created_at = date('Y-m-d H:i:s');
                $model->booked_at = date('Y-m-d H:i:s');
                if($model->save()) {
                    $request_id = $model->id;
                    $reference_no = Yii::$app->common->generatereferencenumber($request_id);
                    $model->reference_no = $reference_no;
                    if($model->save(false)){
                        $todolist = new TodoList();
                        $todolist->user_id = $model->user_id;
                        $todolist->service_request_id = $request_id;
                        $todolist->property_id = $model->property_id;
                        $todolist->vendor_id = $model->vendor_id;
                        $todolist->reftype = 'Service';
                        $todolist->service_type = 'Mover';
                        $todolist->created_at =  date("Y-m-d H:i:s");
                        $todolist->updated_at =  date("Y-m-d H:i:s");
                        $todolist->status = 'New';
                        if($todolist->save(false)){
                            $model->todo_id = $todolist->id;
                            $model->save(false);
                            $subject = 'Service order placed';
                            $textmessage = 'You got one service order pending for action, kindly accept now.';
                            Yii::$app->common->Savenotification($todolist->vendor_id,$subject,$textmessage,'',$model->property_id,$todolist->id);

                            Yii::$app->common->Sendpushnotification($todolist->vendor_id,$subject,$textmessage,'Partner');

                            return $this->redirect(['index']);

                        }else{
                            return $this->render('createmoverorder', [
                                'model' => $model
                            ]);
                        }
                    }
                }else{
                    return $this->render('createmoverorder', [
                        'model' => $model
                    ]);
                }

            }else{
                return $this->render('createmoverorder', [
                    'model' => $model
                ]);
            }
        } else {
            return $this->render('createmoverorder', [
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
        date_default_timezone_set("Asia/Kuala_Lumpur");
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
    public function actionAssignvendor($id)
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $merchantmodel = $this->findModel($id);
        if(($merchantmodel->reftype=='Laundry' || $merchantmodel->reftype=='Cleaner')){
            throw new NotFoundHttpException('The requested page does not exist.');

        }
        if($merchantmodel->status!='Confirmed'){
            throw new NotFoundHttpException('The requested page does not exist.');

        }
        $merchantmodel->scenario = 'assigndriver';


        if ($merchantmodel->load(Yii::$app->request->post()) ) {
            $vendormodel = Users::findOne($merchantmodel->vendor_id);
            if($vendormodel->current_status=='Busy'){
                throw new NotFoundHttpException('This Vendor already assigned to other request.Please try different');

            }
            // $model->picture = \yii\web\UploadedFile::getInstance($model, 'picture');
            if($merchantmodel->validate()) {
                $merchantmodel->status = 'In Progress';
                $merchantmodel->updated_at = date('Y-m-d H:i:s');
                if($merchantmodel->save(false)) {
                    $todolist = $merchantmodel->todo;
                    $todolist->status = $merchantmodel->status;
                    $todolist->vendor_id = $merchantmodel->vendor_id;
                    $todolist->updated_at = date('Y-m-d H:i:s');
                    if($todolist->save(false)) {
                        $vendormodel->current_status = 'Busy';
                        $vendormodel->updated_at = date('Y-m-d H:i:s');
                        $vendormodel->save(false);
                        $subject = 'Service order placed';
                        $textmessage = 'You got one service order pending for action, kindly accept now.';
                        Yii::$app->common->Savenotification($todolist->vendor_id,$subject,$textmessage,'',$todolist->property_id,$todolist->id);

                        Yii::$app->common->Sendpushnotification($todolist->vendor_id,$subject,$textmessage,'Partner');

                        return $this->redirect(['index']);
                    }
                }

            }else{
                return $this->render('assignvendor', [
                    'merchantmodel' => $merchantmodel,
                    'reassign' => false
                    //'images' => $images
                ]);
            }
        }else {
            $servicetype = $merchantmodel->reftype;
            return $this->render('assignvendor', [
                'merchantmodel' => $merchantmodel,
                'reassign' => false

            ]);

        }
    }
    public function actionReassignvendor($id)
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $merchantmodel = $this->findModel($id);
        $oldvendor = $merchantmodel->vendor_id;
        if($merchantmodel->reftype!='Cleaner'){
            throw new NotFoundHttpException('The requested page does not exist.');

        }
        if($merchantmodel->status!='New'  && $merchantmodel->status!='Unpaid'){

            throw new NotFoundHttpException('The requested page does not exist.');

        }
        $merchantmodel->scenario = 'reassigndriver';


        if ($merchantmodel->load(Yii::$app->request->post()) ) {
            $oldvendormodel = Users::findOne($merchantmodel->vendor_id);
            if($oldvendormodel->current_status=='Busy'){
                throw new NotFoundHttpException('This Vendor already assigned to other request.Please try different');

            }
            // $model->picture = \yii\web\UploadedFile::getInstance($model, 'picture');
            if($merchantmodel->validate()) {
                $merchantmodel->updated_at = date('Y-m-d H:i:s');
                if($merchantmodel->save(false)) {
                    $vendormodel = Users::findOne($oldvendor);
                    $todolist = $merchantmodel->todo;
                    $todolist->vendor_id = $merchantmodel->vendor_id;
                    $todolist->updated_at = date('Y-m-d H:i:s');
                    if($todolist->save(false)) {
                        $vendormodel->current_status = 'Free';
                        $vendormodel->updated_at = date('Y-m-d H:i:s');
                        $vendormodel->save(false);

                        $oldvendormodel->current_status = 'Busy';
                        $oldvendormodel->updated_at = date('Y-m-d H:i:s');
                        $oldvendormodel->save(false);
                        $subject = 'Service order placed';
                        $textmessage = 'You got one service order pending for action, kindly accept now.';
                        Yii::$app->common->Savenotification($todolist->vendor_id,$subject,$textmessage,'',$todolist->property_id,$todolist->id);

                        Yii::$app->common->Sendpushnotification($todolist->vendor_id,$subject,$textmessage,'Partner');


                        return $this->redirect(['index']);
                    }
                }

            }else{
                return $this->render('assignvendor', [
                    'merchantmodel' => $merchantmodel,
                    'reassign' => true
                ]);
            }
        }else {
            $servicetype = $merchantmodel->reftype;
            return $this->render('assignvendor', [
                'merchantmodel' => $merchantmodel,
                'reassign' => true

            ]);

        }
    }

    public function actionRefund($id)
    {date_default_timezone_set("Asia/Kuala_Lumpur");

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
