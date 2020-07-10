<?php

namespace app\controllers;

use app\models\Properties;
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
class ImagesController extends Controller
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
        $searchModel = new ImagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $merchantmodel = Properties::findOne($id);
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
            if ($_FILES) {
                $tmpname = $_FILES['Images']['tmp_name']['images'][0];
                $fname = $_FILES['Images']['name']['images'][0];
                //Get the temp file path
                $tmpFilePath = $tmpname;
                //Make sure we have a filepath
                if ($tmpFilePath != "") {
                    //save the filename
                    $shortname = $fname;
                    $size = $_FILES['Images']['size']['images'][0];
                    $ext = substr(strrchr($shortname, '.'), 1);
                    if (in_array($ext, $allwoedFiles)){
                        //save the url and the file
                        $newFileName = Yii::$app->security->generateRandomString(40) . "." . $ext;
                        //Upload the file into the temp dir
                        if (move_uploaded_file($tmpFilePath, 'uploads/properties/' . $newFileName)) {
                            $productsImages = new Images();
                            $productsImages->property_id = $products_id;
                            $productsImages->image = 'uploads/properties/' . $newFileName;
                            $productsImages->created_at = date('Y-m-d h:i:s');
                            $productsImages->save();
                            $files['initialPreview'] = \yii\helpers\Url::base(TRUE) . '/uploads/properties/' . $newFileName;
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
                    $files = Images::SaveTempAttachments($_FILES);
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
        $merchantmodel = Properties::findOne($id);

        $model = new Images();
        if (isset($model->images_array) && count($model->images_array) > 0) {
            $images_array = explode(',', $model->images_array);
            if (!empty($images_array) && $model->images_array != '') {
                foreach ($images_array as $image) {
                    $file = Yii::$app->basePath . '/uploads/properties/temp/' . $image;
                    $rename_file = Yii::$app->basePath . '/uploads/properties/' . $image;
                    rename($file, $rename_file);
                    $productsImages = new Images();
                    $productsImages->property_id = $id;
                    $productsImages->image = 'uploads/properties/' . $image;
                    $productsImages->created_at = time();
                    $productsImages->save();
                }
            }

        } else {
            $images = Images::find()->where(['property_id'=>$id])->all();
            return $this->render('create', [
                'model' => $model,
                'merchantmodel'=>$merchantmodel,
                'images'=>$images
            ]);
        }
    }

    public function actionAdd($id)
    {
        $merchantmodel = Properties::findOne($id);

        $model = new Images();
        if (isset($model->images_array) && count($model->images_array) > 0) {
            $images_array = explode(',', $model->images_array);
            if (!empty($images_array) && $model->images_array != '') {
                foreach ($images_array as $image) {
                    $file = Yii::$app->basePath . '/uploads/properties/temp/' . $image;
                    $rename_file = Yii::$app->basePath . '/uploads/properties/' . $image;
                    rename($file, $rename_file);
                    $productsImages = new Images();
                    $productsImages->property_id = $id;
                    $productsImages->image = 'uploads/properties/' . $image;
                    $productsImages->created_at = time();
                    $productsImages->save();
                }
            }

        } else {
            $images = Images::find()->where(['property_id'=>$id])->all();
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
     * Updates an existing MerchantBanners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $merchant_id = $model->property_id;
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()) {
                if(!empty($_FILES['MerchantBanners']['name']['picture'])){
                    $model->picture = \yii\web\UploadedFile::getInstance($model, 'picture');
                    $newFileName = \Yii::$app->security
                            ->generateRandomString().'.'.$model->picture->extension;
                    $model->image = $newFileName;
                }

                $model->updated_at = date('Y-m-d h:i:s');
                $model->save();
                if(isset($newFileName)){
                    $model->picture->saveAs('uploads/merchantbanners/' . $newFileName);
                }
                return $this->redirect(['index', 'id' => $merchant_id]);
            }else{
                $merchantmodel = Properties::findOne($merchant_id);
                return $this->render('update', [
                    'model' => $model,
                    'id'=>$id,
                    'merchantmodel'=>$merchantmodel
                ]);
            }
        } else {
            $merchantmodel = Properties::findOne($merchant_id);
            return $this->render('update', [
                'model' => $model,
                'merchantmodel'=>$merchantmodel
            ]);
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
