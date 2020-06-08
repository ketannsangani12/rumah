<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "homes_admins".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 */
class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $authKey;
    public $accessToken;
    private $_user = false;
    public $oldpassword;
    public $rememberMe = true;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role', 'first_name','last_name','email'], 'required','on' => 'adduser'],
            [['contact_no','company_name','company_address','company_state','bank_account_name','bank_account_no','bank_name'], 'required','when' => function ($model) {
                return ($model->role == 'Cleaner' || $model->role == 'Mover');
            }, 'whenClient' => "function (attribute, value) {
        return ($('#role').val() == 'Cleaner' || $('#role').val() == 'Mover');
    }"],

            [['email'], 'email'],
            [['email'], 'unique','on'=>'adduser'],
            [['email', 'password'], 'required','on' => 'login'],
            ['password', 'validatePassword','on' => 'login'],
           // [['username','name','password'], 'required','on' => 'createsuperadmin'],
            //[['username','name'], 'required','on' => 'create'],
            //[['oldpassword', 'password'], 'required','on' => 'changepassword'],
            //[['oldpassword'], 'checkoldpassword','on' => 'changepassword'],
            //[['name', 'username', 'password', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['password'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name'=>'First Name',
            'last_name'=>'Last Name',
            'username' => 'Username',
            'password' => 'Password',
            'email'=>'Email',
            'company_name'=>'Company Name',
            'company_address'=>'Company Address',
            'company_state'=>'Company State',
            'address'=>'Address',
            'state'=>'State',
            'registration_no'=>'Registration No',
            'bank_account_name'=>'Bank Account Name',
            'bank_account_no'=>'Bank Account No.',
            'bank_name'=>'Bank Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return Users::findOne(['email'=>$username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function validatePassword1($password)
    {
        //echo $this->password."--->".md5($password);exit;
        return $this->password === md5($password);
    }
    public function checkoldPassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = Users::findOne(['id'=>Yii::$app->user->id]);

            if (!$user || !$user->validatePassword1($this->oldpassword)) {
                $this->addError($attribute, 'Old password is wrong.');
            }
        }
    }
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword1($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login()
    {
        //var_dump($this->validate());exit;
        if ($this->validate()) {
            //var_dump($this->getUser());exit;
            return Yii::$app->user->login($this->getUser());
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Users::findByUsername($this->email);

            //
        }

        return $this->_user;
    }
    public static function actionbuttons($model,$controller){
        $actionsbuttons = array(
            'view' => function ($url, $model) {

                return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/view', 'id' => $model->id])], [

                    'title' => 'View',
                    'class'=>'btn btn-sm btn-primary datatable-operation-btn'

                ]);

            },
            'update' => function ($url, $model) {

                return Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/update', 'id' => $model->id])], [

                    'title' => 'Update',
                    'class' =>'btn btn-sm btn-warning datatable-operation-btn'

                ]);

            },
            'delete' => function ($url, $model) {

                return Html::a('<i class="fa fa-trash" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/delete', 'id' => $model->id])], [

                    'title' => 'Delete',
                    'class' =>'btn btn-sm btn-danger datatable-operation-btn',
                    'data-confirm' => \Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method'  => 'post',

                ]);

            },
        );
        return $actionsbuttons;
    }
    public static function findIdentity($id)
    {
        return Users::findOne(['id'=>$id]);
    }


}
