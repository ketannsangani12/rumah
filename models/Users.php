<?php

namespace app\models;

use Lcobucci\JWT\Signer\Ecdsa\Sha512;
use Lcobucci\JWT\Signer\Hmac\Sha256;
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
    public $referral_code;
    private $_user = false;
    public $oldpassword;
    public $rememberMe = true;
    protected $token;
    public $newpassword;
    public $agentcard;
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
            [['email','password'], 'required','on' => 'login'],
            [['role', 'full_name','email'], 'required','on' => 'adduser'],
            [['full_name','email','password','contact_no'], 'required','on' => 'register'],
            [['company_name','email','password','contact_no','document_no','agentcard'], 'required','on' => 'registeragent'],
            [['agentcard'], 'file', 'skipOnEmpty' => false,'extensions' => 'png,jpg,jpeg','on'=>'registeragent'],

            [['gender','dob','race','nationality','education_level','occupation','annual_income','contact_no','emergency_contact'], 'required','on' => 'updateprofileuser'],
            [['company_name','document_no','bank_account_name','bank_account_no','bank_name'], 'required','on' => 'updateprofileagent'],

            ['referral_code', 'checkReferralcode'],
            [['contact_no','company_name','company_address','company_state','bank_account_name','bank_account_no','bank_name'], 'required','when' => function ($model) {
                return ($model->role == 'Handyman' || $model->role == 'Mover');
            }, 'whenClient' => "function (attribute, value) {
        return ($('#role').val() == 'Handyman' || $('#role').val() == 'Mover');
    }"],
            [['contact_no','company_name','company_address','company_state','bank_account_name','bank_account_no','bank_name','latitude','longitude'], 'required','when' => function ($model) {
                return ($model->role == 'Laundry' || $model->role == 'Cleaner');
            }, 'whenClient' => "function (attribute, value) {
        return ($('#role').val() == 'Laundry' || $('#role').val() == 'Cleaner');
    }"],

            [['email'], 'email'],
            [['email'], 'unique','on'=>'adduser'],
            [['email'], 'unique','on'=>'register'],
            [['email'], 'unique','on'=>'registeragent'],
            [['contact_no'], 'unique','on'=>'register'],
            [['contact_no'], 'unique','on'=>'registeragent'],
            [['contact_no'], 'unique','on'=>'adduser'],
            [['email', 'password'], 'required','on' => 'login'],
            ['password', 'validatePassword','on' => 'login'],
            [['bank_account_name','bank_account_no','bank_name'], 'required','on' => 'adduseraccount'],
            //[['username','name'], 'required','on' => 'create'],
            [['oldpassword', 'newpassword'], 'required','on' => 'changepassword'],
            //[['oldpassword'], 'checkoldpassword','on' => 'changepassword'],
            [['secondary_password'], 'required', 'on' => 'createsecondarypassword'],
            [['wallet_balance'], 'number'],

            //[['name', 'username', 'password', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['password'], 'string', 'max' => 255],
            [['username','current_status'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name'=>'Full Name',
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
            'gender'=>'Gender',
            'dob'=>'DOB',
            'agent_card'=>'Agent Card',
            'agentcard'=>'Agent Card',
            'race'=>'Race',
            'nationality'=>'Nationality',
            'education_level'=>'Education Level',
            'occupation'=>'Occupation',
            'annual_income'=>'Annual Income',
            'oldpassword'=>'Old Password',
            'newpassword'=>'New Password',
            'emergency_contact'=>'Emergency Contact',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'referral_code'=>'Referral Code',
            'current_status'=>'Current Status',
            'latitude'=>'Latitude',
            'longitude'=>'Longitude',
            'wallet_balance'=>'Wallet Balance'
        ];
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        $token = \Yii::$app->jwt->getParser()->parse((string) $token); // Parses from a string
        $signer = new Sha256();

        $data = \Yii::$app->jwt->getValidationData(); // It will use the current time to validate (iat, nbf and exp)
        $data->setIssuer(\Yii::$app->params[ 'hostInfo' ]);
        $data->setAudience(\Yii::$app->params[ 'hostInfo' ]);
        $data->setId('myid');

        $username = $data->getClaim('username');

        if ($token->validate($data) && $token->verify($signer, 'testing')) {
            return static::findByUsername($username);
        }

        return null;
    }


    public function getToken()
    {
        return $this->token;
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

            public static function generateJwt () {
                $jwt = Yii::$app->jwt;
                $signer = $jwt->getSigner('HS256');
                $key = $jwt->getKey();
                $time = time();

                // Previous implementation
                /*
                $token = $jwt->getBuilder()
                    ->setIssuer('http://example.com')// Configures the issuer (iss claim)
                    ->setAudience('http://example.org')// Configures the audience (aud claim)
                    ->setId('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
                    ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
                    ->setExpiration(time() + 3600)// Configures the expiration time of the token (exp claim)
                    ->set('uid', 100)// Configures a new claim, called "uid"
                    ->sign($signer, $jwt->key)// creates a signature using [[Jwt::$key]]
                    ->getToken(); // Retrieves the generated token
                */

                // Adoption for lcobucci/jwt ^4.0 version
                $time = time();
                $token = Yii::$app->jwt->getBuilder()
                    ->issuedBy('http://example.com') // Configures the issuer (iss claim)
                    ->permittedFor('http://example.org') // Configures the audience (aud claim)
                    ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
                    ->issuedAt($time) // Configures the time that the token was issue (iat claim)
                    ->canOnlyBeUsedAfter($time + 60) // Configures the time that the token can be used (nbf claim)
                    ->expiresAt($time + 3600) // Configures the expiration time of the token (exp claim)
                    ->withClaim('uid', 1) // Configures a new claim, called "uid"
                    ->getToken(); // Retrieves the generated token
                return $token;
            }


    public static function generateToken($userexist)
    {
        $signer = new Sha256();
        $token = \Yii::$app->jwt->getBuilder()->setIssuer(\Yii::$app->params[ 'hostInfo' ]) // Configures the issuer (iss claim)
        ->setAudience(\Yii::$app->params[ 'hostInfo' ]) // Configures the audience (aud claim)
        ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
        ->setNotBefore(time()) // Configures the time before which the token cannot be accepted (nbf claim)
        ->setExpiration(time() + 7890000) // Configures the expiration time of the token (exp claim)
        ->withClaim('uid', $userexist) // Configures a new claim, called "uid"
        ->sign($signer, 'testing') // creates a signature using "testing" as key
        ->getToken(); // Retrieves the generated token

        return $token;
    }

    public static function getReferralCode($id)
    {
        if ($id && $id != null && $id != '' && is_numeric($id)) {
            $encrypted = (((((($id * 3) + 213) * 5) + 945) - 157) - 28) * 3;
            return $encrypted;
        } else {
            return null;
        }
    }

    public static function getUserIdFromReferralCode($id)
    {
        if ($id && $id != null && $id != '' && is_numeric($id)) {
            $decrypted = (((((($id / 3) + 28) + 157) - 945) / 5) - 213) / 3;
            return $decrypted;
        } else {
            return null;
        }
    }

    public static function getbalance($user_id)
    {
        $userdetails = Users::findOne($user_id);
        return $userdetails->wallet_balance;
    }

    public static function getcoinsbalance($user_id)
    {
        $userdetails = Users::findOne($user_id);
        return $userdetails->coins;
    }
    public static function updatebalance($walletbalance,$user_id)
    {
        $usermodel = Users::findOne($user_id);
        $usermodel->wallet_balance=$walletbalance;
        if($usermodel->save(false)){
            return true;
        }else{
           return false;
        }

        //return true;
    }
    public static function updatecoinsbalance($walletbalance,$user_id)
    {
        $usermodel = Users::findOne($user_id);
        $usermodel->coins=$walletbalance;
        if($usermodel->save(false)){
            return true;
        }else{
            return false;
        }

        //return true;
    }
    public function checkReferralcode($attribute, $params)
    {
        $referall_id = $this->getUserIdFromReferralCode($this->referral_code);
        if($referall_id!=null){
            $referral_user = Users::findOne($referall_id);
            if(empty($referral_user) ){
                    $this->addError($attribute, 'Please enter Valid Referral Code.');
            }
        }else{
            $this->addError($attribute, 'Please enter Valid Referral Code.');
        }
        // no real check at the moment to be sure that the error is triggered

    }
    }
