<?php
class AppController extends Controller{    
    public function actionItunesProduct() {
        $cfgPurchase = Util::loadConfig('ItunesPurchase');
        $products = $cfgPurchase['products'];
        
        $this->renderPartial('itunesProduct',array(
            'products' => $products,
            'callbackUrl' => $this->createAbsoluteUrl('app/itunesPurchaseCallback'),
            'tm' => time(),
        ));
    }
    
    public function actionItunesPurchaseCallback(){
        //method check
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $this->renderPartial('itunesPurchaseCallback',array(
                'flag' => '403',
                'title' => 'Error',
                'message' => 'Invalid Method!',
            ));
            return;
        }
        
        //param check
        $tm = time();
        $va = new Validator();
        $va->check(array(
            'uid' => array('not_blank',array('length',1,64)),
            'tid' => array('not_blank',array('length',1,255)),
            'tm' => array('not_blank',array('uint_range',$tm-86400,$tm+86400)),
            'sig' => array('not_blank'),
        ));
        if(!$va->success){
            $this->renderPartial('itunesPurchaseCallback',array(
                'flag' => '403',
                'title' => 'Error',
                'message' => 'Invalid Params!',
            ));
            return;
        }
        
        //sig check
        $cfgPurchase = Util::loadConfig('ItunesPurchase');
        $sig = hash('sha256',$va->valid['uid'].$va->valid['tid'].$va->valid['tm'].$cfgPurchase['SigKey']);
        if($sig != $va->valid['sig']){
            $this->renderPartial('itunesPurchaseCallback',array(
                'flag' => '403',
                'title' => 'Error',
                'message' => 'Invalid Signature!',
            ));
            return;
        }
        
        //data check
        //$receiptData = file_get_contents('php://input');
        $receiptData = $_REQUEST['data'];
        error_log($receiptData);
        if(!$receiptData){
            $this->renderPartial('itunesPurchaseCallback',array(
                'flag' => '403',
                'title' => 'Error',
                'message' => 'Invalid BodyData!',
            ));
            return;
        }
        
//        session_id($va->valid['uid']);
//        session_start();
//        if(!isset($_SESSION['sns_id'])){
//            $this->renderPartial('itunesPurchaseCallback',array(
//                'flag' => '403',
//                'title' => 'Error',
//                'message' => 'Invalid User!',
//            ));
//            return;
//        }
//        $sns_id = $_SESSION['sns_id'];
        $sns_id = $va->valid['uid'];

        $flag = 500;//unknown error
        $itunesPaymentTransaction = Yii::app()->objectLoader->load('ItunesPaymentTransaction', $va->valid['tid']);
        if($itunesPaymentTransaction->isExists() && $itunesPaymentTransaction->transaction_status==0){
            $flag = 2;//success(repeat commit)
        }else{
            $verifyContent = '';
            $verifyDir = Yii::app()->getRuntimePath().'/../data/itunesTransactionVerify/';
            $verifyFile = $verifyDir.$va->valid['tid'].'.itnsvfy';
            if(file_exists($verifyFile)){
                $verifyContent = file_get_contents($verifyFile);
            }else{
                $receiptVerifyUrl = $cfgPurchase['ReceiptVerifyUrl'];
                $verifyContent = Util::postRequest($receiptVerifyUrl,json_encode(array('receipt-data'=>$receiptData)));
                //$verifyContent = Util::postRequest($receiptVerifyUrl,json_encode(array('receipt-data'=>base64_encode($receiptData))));
                //$verifyContent = Util::postRequest($receiptVerifyUrl, $receiptData);
            }
            if($verifyContent){
                $verifyResult = json_decode($verifyContent,true);
                if(isset($verifyResult['receipt'])){
                    $dba = Yii::app()->db;
                    
                    $transaction_id = $verifyResult['receipt']['transaction_id'];
                    $product_id = $verifyResult['receipt']['product_id'];
                    $quantity = $verifyResult['receipt']['quantity'];
                    if(isset($cfgPurchase['products'][$product_id])){
                        $productConf = $cfgPurchase['products'][$product_id];
                        $price = $productConf['price'];
                        $purchaseGold = $productConf['purchaseGold'] * $quantity;
                        $systemGold = $productConf['systemGold'] * $quantity;
                        if($verifyResult['status'] == 0){
                            if(!file_exists($verifyDir)){
                                @mkdir($verifyDir);
                            }
                            if(!file_exists($verifyFile)){
                                @file_put_contents($verifyFile,$verifyContent);
                            }
                            
                            $flag = 1;//success
                            $transaction = $dba->beginTransaction();
                            try {
                                $cmd = $dba->createCommand("insert into `ItunesPaymentTransaction` (`sns_id`,`transaction_id`,`transaction_status`,`product_id`,`price`,`quantity`,`purchaseGold`,`systemGold`,`record_time`) 
                                values (:sns_id,:transaction_id,0,:product_id,:price,:quantity,:purchaseGold,:systemGold,unix_timestamp()) 
                                on duplicate key update `transaction_status`=0");
                                $affect_rows = $cmd->execute(array(
                                    ':sns_id' => $sns_id,
                                    ':transaction_id' => $transaction_id,
                                    ':product_id' => $product_id,
                                    ':price' => $price,
                                    ':quantity' => $quantity,
                                    ':purchaseGold' => $purchaseGold,
                                    ':systemGold' => $systemGold,
                                ));
                                if($affect_rows > 0){
                                    $itunesPaymentTransaction = Yii::app()->objectLoader->load('ItunesPaymentTransaction', $transaction_id);
                                    if($itunesPaymentTransaction->isExists()){
                                        if (isset($_GET['uid'])) {
                                            $userId = User::findIdByDevice($_GET['uid']);
                                            if (isset($userId)) {
                                                $itunesPaymentTransaction->userId = $userId;
                                            }
                                            $itunesPaymentTransaction->saveAttributes(array('userId'));
                                            
                                            $playerId = Player::findIdByUserId($userId);
                                            $playerMoney = Yii::app()->objectLoader->load('PlayerMoney', $playerId);
                                            if (!empty($itunesPaymentTransaction->purchaseGold)) {
                                                $playerMoney->add($itunesPaymentTransaction->purchaseGold, 'itunes purchase');
                                            }
                                            if (!empty($itunesPaymentTransaction->systemGold)) {
                                                $playerMoney->send($itunesPaymentTransaction->systemGold, 'itunes purchase');
                                            }
                                            
                                            $transaction->commit();

                                            //成就检查
                                            
                                            $achieveEvent = new AchievementEvent($playerId, ACHIEVEEVENT_ITUNES);
                                            $achieveEvent->onAchieveComplete(); 
                                        }
                                    }
                                }
                            } catch(Exception $e) {
                                $flag = 501;
                                $transaction->rollback();
                            }
                        }else{
                            $flag = 102;//transaction not finish
                            $cmd = $dba->createCommand("insert ignore into `ItunesPaymentTransaction` (`sns_id`,`transaction_id`,`transaction_status`,`product_id`,`price`,`quantity`,`purchaseGold`,`systemGold`,`record_time`) 
                            values (:sns_id,:transaction_id,:transaction_status,:product_id,:price,:quantity,:purchaseGold,:SystemGold,unix_timestamp());");
                            $cmd->execute(array(
                                ':sns_id' => $sns_id,
                                ':transaction_id' => $transaction_id,
                                ':transaction_status' => $verifyResult['status'],
                                ':product_id' => $product_id,
                                ':price' => $price,
                                ':quantity' => $quantity,
                                ':purchaseGold' => $purchaseGold,
                                ':systemGold' => $systemGold,
                            ));
                        }
                    }else{
                        $flag = 101;//product not exist
                        $cmd = $dba->createCommand("insert ignore into `ItunesPaymentTransaction` (`sns_id`,`transaction_id`,`transaction_status`,`product_id`,`price`,`quantity`,`purchaseGold`,`systemGold`,`record_time`) 
                        values (:sns_id,:transaction_id,101,:product_id,0,:quantity,0,0,unix_timestamp());");
                        $cmd->execute(array(
                            ':sns_id' => $sns_id,
                            ':transaction_id' => $transaction_id,
                            ':product_id' => $product_id,
                            ':quantity' => $quantity,
                        ));
                    }
                }
            }
        }
        
        $this->display(array('flag' => $flag));
    }
}
?>
