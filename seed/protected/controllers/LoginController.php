<?php
class LoginController extends Controller
{
    public function filters()
    {
        return array(
        	'getSize',    
        );
    }

    public function actionTimeout() {
        $this->display('timeout');
    }

    public function actionIndex()
    {
        if( empty($_REQUEST['deviceId'])/*&&SeedConfig::isDebug()*/ ) {
            $this->redirect( $this->createUrl('login/timeout') );
        }

        $session = Yii::app()->session;
        $session->open();

        $deviceId = isset($_REQUEST['deviceId'])?$_REQUEST['deviceId']:'' ;
        $token = isset($_REQUEST['token'])?$_REQUEST['token']:'' ;
        $salt = isset($_REQUEST['salt'])?$_REQUEST['salt']:'' ;
        $sign = isset($_REQUEST['sign'])?$_REQUEST['sign']:'' ;
        $checkSign = md5($deviceId.'|'.$token.'|'.$salt.'|'.RECEIVE_TOKEN_KEY);
        if( $sign!=$checkSign ) {
            throw new SException('login failed!');
        }

        $userId = User::findIdByDevice($deviceId);
        if (!$userId) {
            $userId = User::create( array('deviceId' => $deviceId,'token'=>$token) );
        }
        elseif( !empty($token) ) {
            $user = Yii::app()->objectLoader->load('User',$userId);
            if( $token!=$user->token ) {
                $user->token = $token ;
                $user->saveAttributes( array('token') );
            }
        }
        $session->clear();
        $session['userId'] = $userId;
        if (isset($_REQUEST['isipad'])) {
        	$session['size'] = SeedConfig::setSize($_REQUEST['isipad']);
        }

        $playerId = Player::findIdByUserId($userId);

        if ($playerId) {
            $player = Yii::app()->objectLoader->load('Player', $playerId);
            $player->sessionId = $session->getSessionId();
            $player->saveAttributes( array('sessionId') );

            $playerLogin = Yii::app()->objectLoader->load('PlayerLoginModel', $playerId);
            $playerLogin->refreshLogin();
            
            //如果处在新手引导阶段则不跳出EVENT
            if (/*Yii::app()->objectLoader->load('GuideModel',$playerId)->isNewUserGuide()*/1) {
                if ($this->actionType === REQUEST_TYPE_NATIVE_API) {
                    $defaultGarden = Player::findDefaultGardenByPlayerId($playerId);
                    $this->display(
                        array('playerId'=>$playerId, 'defaultGarden'=>$defaultGarden),
                        array('message'=>'loginsuccess')
                    );
                } else {
                    $this->redirect($this->createUrl('player/getIndex'));
                }
            } else {
                if ($this->actionType === REQUEST_TYPE_NATIVE_API) {
                    $this->display($this->createAbsoluteUrl('event/start'), array('message'=>'register'));
                } else {
                    $this->redirect($this->createUrl('player/getIndex'));
                }
            }
            
        } else {
            if ($this->actionType === REQUEST_TYPE_NATIVE_API) {
                $this->display($this->createAbsoluteUrl('register'), array('message'=>'register'));
            } else {
                $this->redirect($this->createUrl('register'));
            }
        }

    }

    public function actionLogin() {
        if( !empty($_REQUEST['deviceId'])/*&&SeedConfig::isDebug()*/ ){
            $deviceId = $_REQUEST['deviceId'] ;
            $token = strlen($deviceId)<30?rand(111111,999999):'' ;
            $salt = time() ;
            $sign = md5($deviceId.'|'.$token.'|'.$salt.'|'.RECEIVE_TOKEN_KEY);
            $this->redirect( $this->createUrl('login/index',array('deviceId'=>$deviceId,'token'=>$token,'salt'=>$salt,'sign'=>$sign)) );
        }
        else {
            $this->display('index');
        }
    }

    public function actionRegister() 
    {
        $session = Yii::app()->session;
        $session->open();
        $userId = $session['userId'];

        $model = new PlayerRegister();

        if(isset($_REQUEST['playerName']))
        {
            $model->playerName = trim($_REQUEST['playerName']);
            //招待ID开关
            $model->inviterId = $_REQUEST['inviterId'];
            //$model->inviterId = '';
            $model->userId = $userId;
            $model->scenario = 'Register';

            //判断是否已注册
            $command = Yii::app()->db->createCommand();
            $command->setText("SELECT playerId FROM player WHERE userId = :userId");
            $command->bindValue(':userId', $userId);
            $playerId = $command->queryScalar();

            if ($playerId or $model->validate()) {
                $inviterId = null;

                $transaction = Yii::app()->db->beginTransaction();
                try {
                    if ($playerId) {
                        $player = Yii::app()->objectLoader->load('Player', $playerId);
                    } else {
                        $player = $model->register();
                    }
                   
                    $player->sessionId = $session->getSessionId();
                    $player->saveAttributes( array('sessionId') );

                    $playerLogin = Yii::app()->objectLoader->load('PlayerLoginModel', $player->playerId);

                    $playerLogin->refreshLogin();
                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollback();
                    throw $e;
                }
                $this->display(array('playerId'=>$player->playerId));

            } else {
                foreach ($model->getErrors() as $attribute=>$errors) {
                    //$message[] = implode("<br />", $errors);
                    //只显示一条error
                    $message[] = $errors[0];
                    break;
                }
                $this->error(implode("<br />", $message));
            }
        }
        $this->display('register');
    }

}
