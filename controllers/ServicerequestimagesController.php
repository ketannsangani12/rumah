<?php

namespace app\controllers;

use app\models\Properties;
use app\models\ServicerequestImages;
use app\models\ServicerequestImagesSearch;
use app\models\ServiceRequests;
use PharIo\Manifest\Url;
use Yii;
use app\models\Images;
use app\models\ImagesSearch;
use yii\base\Response;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ImagesController implements the CRUD actions for Images model.
 */
class ServicerequestimagesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','deleteimage','upload','add','update'],
                'rules' => [
                    [
                        'actions' => ['index','deleteimage','upload','create','add','update'],
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
     * Lists all Images models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $searchModel = new ServicerequestImagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $merchantmodel = ServiceRequests::findOne($id);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'merchantmodel'=>$merchantmodel
        ]);
    }

    /**
     * Displays a single Images model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionUpload() {
        $files = array();
        $allwoedFiles = ['jpg', 'png','jpeg'];
        //print_r($_FILES);exit;
        if ($_POST['is_post'] == 'update') {
            $products_id = $_POST['property_id'];
            $type = $_POST['type'];
           // print_r($_FILES);exit;
            if ($_FILES) {
                $tmpname = $_FILES['ServicerequestImages']['tmp_name']['images'][0];
                $fname = $_FILES['ServicerequestImages']['name']['images'][0];
                //Get the temp file path
                $tmpFilePath = $tmpname;
                //Make sure we have a filepath
                if ($tmpFilePath != "") {
                    //save the filename
                    $shortname = $fname;
                    $size = $_FILES['ServicerequestImages']['size']['images'][0];
                    $ext = substr(strrchr($shortname, '.'), 1);
                    if (in_array($ext, $allwoedFiles)){
                        //save the url and the file
                        $newFileName = Yii::$app->security->generateRandomString(40) . "." . $ext;
                        //Upload the file into the temp dir
                        if (move_uploaded_file($tmpFilePath, 'uploads/servicerequestimages/' . $newFileName)) {
                            $productsImages = new ServicerequestImages();
                            $productsImages->service_request_id = $products_id;
                            $productsImages->image = 'uploads/servicerequestimages/' . $newFileName;
                            $productsImages->reftype = $type;
                            $productsImages->created_at = date('Y-m-d h:i:s');
                            $productsImages->save();
                            $files['initialPreview'] = \yii\helpers\Url::base(TRUE) . '/uploads/servicerequestimages/' . $newFileName;
                            $files['initialPreviewAsData'] = true;
                            $files['initialPreviewConfig'][]['key'] = $productsImages->id;
                            return json_encode($files);
                        }
                    }
                }
            } /* else {
              return json_encode(['error' => 'No files found for pload.']);
              } */
            return json_encode($files);
        } else {
            if (isset($_POST)) {
                if ($_FILES) {
                    $files = ServicerequestImages::SaveTempAttachments($_FILES);
                    return json_encode($files);
                    $result = ['files' => $files];
                    Yii::$app->response->format = trim(\yii\web\Response::FORMAT_JSON);
                    return $result;
                } /* else {
                  echo json_encode(['error' => 'No files found for pload.']);
                  } */
            }
        }
    }
    public function actionCreate($id)
    {
        $merchantmodel = ServiceRequests::findOne($id);
        $servicetype = $merchantmodel->reftype;
        $model = new ServicerequestImages();
        if (isset($model->images_array) && count($model->images_array) > 0) {
            $images_array = explode(',', $model->images_array);
            if (!empty($images_array) && $model->images_array != '') {
                foreach ($images_array as $image) {
                    $file = Yii::$app->basePath . '/uploads/servicerequestimages/temp/' . $image;
                    $rename_file = Yii::$app->basePath . '/uploads/servicerequestimages/' . $image;
                    rename($file, $rename_file);
                    $productsImages = new ServicerequestImages();
                    $productsImages->service_request_id = $id;
                    $productsImages->image = 'uploads/servicerequestimages/' . $image;
                    $productsImages->created_at = time();
                    $productsImages->save();
                }
            }

        } else {
            $query = ServicerequestImages::find()->where(['service_request_id'=>$id]);
                if($servicetype=='Cleaner'){
                    $query->andWhere(['in','reftype',['checkinphoto','checkoutphoto']]);
                }elseif ($servicetype=='Laundry'){
                    $query->andWhere(['in','reftype',['pickupphoto','deliveryphoto']]);
                }elseif ($servicetype=='Handyman'){
                    $query->andWhere(['in','reftype',['useruploadedphotos']]);
                }
            $images =     $query->all();
            return $this->render('create', [
                'model' => $model,
                'merchantmodel'=>$merchantmodel,
                'images'=>$images
            ]);
        }
    }

    public function actionAdd($id)
    {
        $merchantmodel = ServiceRequests::findOne($id);

        $model = new Images();
        if (isset($model->images_array) && count($model->images_array) > 0) {
            $images_array = explode(',', $model->images_array);
            if (!empty($images_array) && $model->images_array != '') {
                foreach ($images_array as $image) {
                    $file = Yii::$app->basePath . '/uploads/servicerequestimages/temp/' . $image;
                    $rename_file = Yii::$app->basePath . '/uploads/servicerequestimages/' . $image;
                    rename($file, $rename_file);
                    $productsImages = new ServicerequestImages();
                    $productsImages->service_request_id = $id;
                    $productsImages->image = 'uploads/servicerequestimages/' . $image;
                    $productsImages->created_at = time();
                    $productsImages->save();
                }
            }

        } else {
            $images = ServicerequestImages::find()->where(['service_request_id'=>$id])->all();
            return $this->render('create', [
                'model' => $model,
                'merchantmodel'=>$merchantmodel,
                'images'=>$images
            ]);
        }
    }
    public function actionDeleteImage() {
        $key = $_POST['key'];
        if (is_numeric($key)) {
            $products_image = Images::find()->where(['id' => $key])->one();
            unlink(Yii::getAlias('@webroot') . '/' . $products_image->image);
            $products_image->delete();
            return true;
        } else {
            unlink(Yii::getAlias('@webroot') . '/uploads/products/temp/' . $key);
            return true;
        }
    }

    /**
     * Deletes an existing Images model.
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
     * Finds the Images model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Images the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Images::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
