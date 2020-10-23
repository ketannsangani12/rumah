<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "tls_email_templates".
 *
 * @property int $id
 * @property string $name
 * @property string $subject
 * @property string $body
 * @property int $is_default
 * @property string $created_at
 * @property string $updated_at
 */
class EmailTemplates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rumah_email_templates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'subject', 'body'], 'required'],
            [['name'], 'unique'],
            [['subject', 'body'], 'string'],
            [['is_default'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'subject' => 'Subject',
            'body' => 'Body',
            'is_default' => 'Is Default',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    public static function getemailtemplate($template,$model,$usermodel,$type=''){

        if(!empty($template)){
           $content = $template->body;
           if($type=='signup' ){
               if($type=='signup') {
                   $url = Url::base(true).'/site/verify?email='.base64_encode($model->email).'&token='.base64_encode($model->verify_token);
                   $anchortag = '<a href="'.$url.'">Click here to verify</a>';
                   $content = str_replace("@link@",$anchortag,$content);
               }

           }
           if(isset($model->password)) {
               $content = str_replace("@password@", $model->password, $content);
           }
            if(isset($model->email)) {
                $content = str_replace("@email@", $model->email, $content);
            }
            if(isset($model->title)) {
                $content = str_replace("@property@", $model->property_no." - ".$model->title, $content);
            }
            if(isset($model->secondary_password)) {
                $content = str_replace("@pin@", $model->secondary_password, $content);
            }
            if(isset($model->full_name)) {
                $content = str_replace("@name@", $model->full_name, $content);
            }
            if(isset($usermodel->full_name)) {
                $content = str_replace("@name@", $usermodel->full_name, $content);
            }
            if(isset($model->first_name)) {
                $content = str_replace("@name@", $model->first_name . " " . $model->last_name, $content);
            }
            if(isset($model->subject)) {
                $content = str_replace("@subject@", $model->subject, $content);
            }
            if(isset($model->message)) {
                $content = str_replace("@message@", $model->message, $content);
            }
            return $content;
        }else{
            return '';
        }

    }

    public static function getemailtemplatefortransfer($template,$reference_no){

        if(!empty($template)){
            $content = $template->body;
            $transaction = Transactions::find()->where(['reference_no'=>$reference_no])->one();
            //if(isset($model->password)) {
            $content = str_replace("@month@", date('M-Y'), $content);
                $content = str_replace("@username@", $transaction->user->first_name." ".$transaction->user->last_name, $content);
                $content = str_replace("@refferenceno@", $transaction->reference_no, $content);
                $content = str_replace("@amount@", number_format((float)$transaction->transfer->amount, 2, '.', ''), $content);
                $content = str_replace("@platformfees@", number_format((float)$transaction->transfer->platform_fees, 2, '.', ''), $content);
            if($transaction->related_transaction_id==''){
                $cashback = (isset($transaction->cashback->cashback_amount))?number_format((float)$transaction->cashback->cashback_amount, 2, '.', ''):number_format((float)0, 2, '.', '');
                //return ($model->transfer->fees>0)?number_format((float)$model->transfer->fees-$total_fees, 2, '.', ''):$total_fees;

            }else if($transaction->related_transaction_id!=''){
                $amount = $transaction->relatedtransaction->amount;
                $cashback = number_format((float)($amount), 2, '.', '');

            }
            $content = str_replace("@cashback@", $cashback, $content);
            $content = str_replace("@refferalfees@", (isset($transaction->referrercashback->cashback_amount))?number_format((float)$transaction->referrercashback->cashback_amount, 2, '.', ''):number_format((float)0, 2, '.', ''), $content);
            if($transaction->related_transaction_id=='') {
                $content = str_replace("@totalamount@", number_format((float)$transaction->transfer->total_amount, 2, '.', ''), $content);
            }else if($transaction->related_transaction_id!=''){
                $amount = $transaction->relatedtransaction->amount;
                $content = str_replace("@totalamount@", number_format((float)$transaction->transfer->total_amount-$amount, 2, '.', ''), $content);

            }
            // }

            return $content;
        }else{
            return '';
        }

    }
    public static function getemailtemplateformonthlyreport($template,$transactiondata,$lastmonth){

        if(!empty($template)){
            $content = $template->body;

            //if(isset($model->password)) {
            $content = str_replace("@month@", $lastmonth, $content);
            $content = str_replace("@amount@", number_format((float)$transactiondata['amount'], 2, '.', ''), $content);
            $content = str_replace("@platformfees@", number_format((float)$transactiondata['platformfees'], 2, '.', ''), $content);

            $content = str_replace("@cashback@", number_format((float)$transactiondata['cashback'], 2, '.', ''), $content);
            $content = str_replace("@refferalfees@", number_format((float)$transactiondata['referral_fees'], 2, '.', ''), $content);
            $content = str_replace("@totalamount@", number_format((float)$transactiondata['totalamount'], 2, '.', ''), $content);
            // }

            return $content;
        }else{
            return '';
        }

    }
}
