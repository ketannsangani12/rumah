<?php
/**
 * Created by PhpStorm.
 * User: ketansangani
 * Date: 12/10/20
 * Time: 10:49 AM
 */

case "fifth";
if ($model->status=='Payment Requested' && $model->user_id==$this->user_id && isset($_POST['amount']) && $_POST['amount']!=''){
    $promocode = (isset($_POST['promo_code']) && $_POST['promo_code']!='')?$_POST['promo_code']:'';
    $amount = (isset($_POST['amount']) && $_POST['amount']!='')?$_POST['amount']:'';
    $discount = (isset($_POST['discount']) && $_POST['discount']!='')?$_POST['discount']:0;
    $goldcoins = (isset($_POST['gold_coins']) && $_POST['gold_coins']!='')?$_POST['gold_coins']:0;
    $coins_savings = (isset($_POST['coins_savings']) && $_POST['coins_savings']!='')?$_POST['coins_savings']:0;
    if($promocode!=''){
        $promocodedetails = PromoCodes::find()->where(['promo_code'=>$promocode])->one();
    }
    $sst = $model->sst;
    $totalamount = $amount;
    $totalamountafterdiscount = (int)$totalamount-(int)$discount-(int)$coins_savings;

    $receiverbalance = Users::getbalance($model->landlord_id);
    $senderbalance = Users::getbalance($model->user_id);
    if($senderbalance < $totalamountafterdiscount){
        return array('status' => 0, 'message' => 'You don"t have enough wallet balance');
        exit;

    }
    $systemaccount = Yii::$app->common->getsystemaccount();
    $systemaccountbalance = $systemaccount->wallet_balance;

    $transaction1 = Yii::$app->db->beginTransaction();

    try {
        $transaction = new Transactions();
        $transaction->user_id = $this->user_id;
        $transaction->request_id = $model->id;
        $transaction->landlord_id = $model->landlord_id;
        $transaction->promo_code = ($promocode!='')?$promocodedetails->id:NULL;
        $transaction->amount = $totalamount;
        $transaction->sst = $sst;
        $transaction->discount = $discount;
        $transaction->coins = $goldcoins;
        $transaction->coins_savings = $coins_savings;
        $transaction->total_amount = $totalamountafterdiscount;
        $transaction->reftype = 'Booking Payment';
        $transaction->status = 'Completed';
        $transaction->created_at = date('Y-m-d H:i:s');
        if ($transaction->save()) {
            $lastid = $transaction->id;
            $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
            $transaction->reference_no = $reference_no;
            if ($transaction->save(false)) {
                if($model->booking_fees>0){
                    $transactionitems = new TransactionsItems();
                    $transactionitems->sender_id = $model->user_id;
                    $transactionitems->receiver_id = $model->landlord_id;
                    $transactionitems->amount = $model->booking_fees;
                    $transactionitems->total_amount = $model->booking_fees;
                    $transactionitems->oldsenderbalance = $senderbalance;
                    $transactionitems->newsenderbalance = $senderbalance-$model->booking_fees;
                    $transactionitems->oldreceiverbalance = $receiverbalance;
                    $transactionitems->newreceiverbalance = $receiverbalance+$model->booking_fees;
                    $transactionitems->description = 'Booking Fees';
                    $transactionitems->created_at = date('Y-m-d H:i:s');
                    $transactionitems->save(false);
                }
                if($model->rental_deposit>0){
                    $transactionitems = new TransactionsItems();
                    $transactionitems->sender_id = $model->user_id;
                    $transactionitems->receiver_id = $model->landlord_id;
                    $transactionitems->amount = $model->rental_deposit;
                    $transactionitems->total_amount = $model->rental_deposit;
                    $transactionitems->oldsenderbalance = $senderbalance;
                    $transactionitems->newsenderbalance = $senderbalance-$model->rental_deposit;
                    $transactionitems->oldreceiverbalance = $receiverbalance;
                    $transactionitems->newreceiverbalance = $receiverbalance+$model->rental_deposit;
                    $transactionitems->description = 'Deposit';
                    $transactionitems->created_at = date('Y-m-d H:i:s');
                    $transactionitems->save(false);
                }
                if($model->keycard_deposit>0){
                    $transactionitems = new TransactionsItems();
                    $transactionitems->sender_id = $model->user_id;
                    $transactionitems->receiver_id = $model->landlord_id;
                    $transactionitems->amount = $model->keycard_deposit;
                    $transactionitems->total_amount = $model->keycard_deposit;
                    $transactionitems->oldsenderbalance = $senderbalance;
                    $transactionitems->newsenderbalance = $senderbalance-$model->keycard_deposit;
                    $transactionitems->oldreceiverbalance = $receiverbalance;
                    $transactionitems->newreceiverbalance = $receiverbalance+$model->keycard_deposit;
                    $transactionitems->description = 'Keycard Deposit';
                    $transactionitems->created_at = date('Y-m-d H:i:s');
                    $transactionitems->save(false);
                }
                if($model->utilities_deposit>0){
                    $transactionitems = new TransactionsItems();
                    $transactionitems->sender_id = $model->user_id;
                    $transactionitems->receiver_id = $model->landlord_id;
                    $transactionitems->amount = $model->utilities_deposit;
                    $transactionitems->total_amount = $model->utilities_deposit;
                    $transactionitems->oldsenderbalance = $senderbalance;
                    $transactionitems->newsenderbalance = $senderbalance-$model->utilities_deposit;
                    $transactionitems->oldreceiverbalance = $receiverbalance;
                    $transactionitems->newreceiverbalance = $receiverbalance+$model->utilities_deposit;
                    $transactionitems->description = 'Utilities Deposit';
                    $transactionitems->created_at = date('Y-m-d H:i:s');
                    $transactionitems->save(false);
                }
                if($model->stamp_duty>0){
                    $transactionitems = new TransactionsItems();
                    $transactionitems->sender_id = $model->user_id;
                    $transactionitems->receiver_id = $systemaccount->id;
                    $transactionitems->amount = $model->stamp_duty;
                    $transactionitems->total_amount = $model->stamp_duty;
                    $transactionitems->oldsenderbalance = $senderbalance;
                    $transactionitems->newsenderbalance = $senderbalance-$model->stamp_duty;
                    $transactionitems->oldreceiverbalance = $systemaccountbalance;
                    $transactionitems->newreceiverbalance = $systemaccountbalance+$model->stamp_duty;
                    $transactionitems->description = 'Stamp Duty';
                    $transactionitems->created_at = date('Y-m-d H:i:s');
                    $transactionitems->save(false);
                }
                if($model->tenancy_fees>0){
                    $transactionitems = new TransactionsItems();
                    $transactionitems->sender_id = $model->user_id;
                    $transactionitems->receiver_id = $systemaccount->id;
                    $transactionitems->amount = $model->tenancy_fees;
                    $transactionitems->total_amount = $model->tenancy_fees;
                    $transactionitems->oldsenderbalance = $senderbalance;
                    $transactionitems->newsenderbalance = $senderbalance-$model->tenancy_fees;
                    $transactionitems->oldreceiverbalance = $systemaccountbalance;
                    $transactionitems->newreceiverbalance = $systemaccountbalance+$model->tenancy_fees;
                    $transactionitems->description = 'Tenancy Fees';
                    $transactionitems->created_at = date('Y-m-d H:i:s');
                    $transactionitems->save(false);
                }
                $model->status = 'Rented';
                $model->rented_at = date('Y-m-d H:i:s');
                if ($model->save(false)) {
                    $todomodel->status = 'Paid';
                    $todomodel->save(false);
                    $model->property->status = 'Rented';
                    $model->property->request_id = $model->id;
                    if($model->property->save()){
                        if($goldcoins>0){
                            $usercoinsbalance = Users::getcoinsbalance($model->user_id);
                            $goldtransaction = new GoldTransactions();
                            $goldtransaction->user_id = $model->user_id;
                            $goldtransaction->gold_coins = $goldcoins;
                            $goldtransaction->transaction_id = $lastid;
                            $goldtransaction->olduserbalance =$usercoinsbalance;
                            $goldtransaction->newuserbalance = $usercoinsbalance-$goldcoins;
                            $goldtransaction->reftype = 'In App Purchase';
                            $goldtransaction->created_at = date('Y-m-d H:i:s');
                            if($goldtransaction->save(false)){
                                Users::updatecoinsbalance($usercoinsbalance-$goldcoins,$model->user_id);
                            }
                        }
                        $updatesenderbalance = Users::updatebalance($senderbalance-$totalamountafterdiscount,$model->user_id);
                        $updatereceiverbalance = Users::updatebalance($receiverbalance+$model->booking_fees+$model->rental_deposit+$model->utilities_deposit+$model->keycard_deposit,$model->landlord_id);
                        $updatesystemaccountbalance = Users::updatebalance($systemaccountbalance+$model->tenancy_fees+$model->stamp_duty+$sst,$systemaccount->id);

                        $transaction1->commit();
                        return array('status' => 1, 'message' => 'You have rented property successfully.');


                    }else{
                        $transaction1->rollBack();
                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                    }


                }else{
                    $transaction1->rollBack();

                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                }

            }else{
                $transaction1->rollBack();

                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

            }


        } else {
            $transaction1->rollBack();

            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

        }
    }catch (Exception $e) {
        // # if error occurs then rollback all transactions
        $transaction1->rollBack();
    }
    //$transaction
}else{
    return array('status' => 0, 'message' => 'Please enter mandatory fields.');

}