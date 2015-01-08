<?php
#Author: Tang.Hongbo
#Email: tang.hongbo@sh.adways.net
#Time: 2012-07-26
class APNS {

    protected $_sandboxMode;
    protected $_debug;

    static protected $sslPemName = array(
            'apns_production_cert_php.pem',
            'apns_development_cert_php.pem'
    );

    public function __construct($sandboxMode = true, $debug = false) {
        $this->_sandboxMode = $sandboxMode;
        $this->_debug = $debug;
    }

    public static function pushNotification($pushNotifications = array(), $sandboxMode = true, $debug = false) {

		$pemPath    = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cert_and_key' . DIRECTORY_SEPARATOR;
		$sslPem     = $pemPath . ($sandboxMode ? self::$sslPemName[1] : self::$sslPemName[0]);

        $pushEngine = new ApnStupid_Push($sandboxMode);
        $pushEngine->setSslPem($sslPem);
        $pushEngine->setPassPhrase('adways');
 
        $errorUserId = 0;
        $allSended = false;
        $hasError = false;
		while (!$allSended)
		{
            $aError = false;
            $pushEngine->apnsConnect();
			foreach($pushNotifications as $pushNotification){
                if ($hasError) {
                    if ($pushNotification['userid'] <> $errorUserId) {
                        continue;
                    } else {
                        $errorUserId = 0;
                        $hasError = false;
                        continue;
                    }
                }
                $identifier = $pushNotification['userid'];
#TODO set the expiry by $pushNotification['expiry']
                $expiry = 30*24*60*60; 
                $token = $pushNotification['token'];
				$content = $pushNotification['content'];

                $messageEngine = new ApnStupid_Message;
                $messageEngine->setIdentifier($identifier);
                $messageEngine->setExpiry($expiry);
                $messageEngine->setToken($token);
                $messageEngine->setPayload($content);

                $message = $messageEngine->getMessage();
                $msgLength = strlen($message);
				$sendSum = 0;
				$retry = 0;
#TODO to read error after sended every message
				while (($msgLength <> $sendSum) and ($retry < 3)){
					try {
						$sendSum = (int)$pushEngine->apnsSend($message);
						if ($debug) {
							print "SendSum: $sendSum, Userid: $identifier.\n";
                            print "Token: $token \n";
                            print "Message: $message\n";
						}
					} catch(ApnStupid_Exception $e) {
                        print $e->getMessage(). " UserId: " . $identifier . "\n";
                        $hasError = true;
                        $errorUserId = $identifier;
                        break 2;
					}
                    $retry++;
				}
                $aError = false;
                $pushEngine->setBlocking(0);
                $aError = $pushEngine->readError();
                $pushEngine->setBlocking(1);
                if (isset($aError['identifier'])) {
                    $hasError = true;
                    $errorUserId = $aError['identifier'];
                    break;
                }
			}

            if ($aError === false) {
                $aError = $pushEngine->readLastError();
                if (isset($aError['identifier'])) {
                    $hasError = true;
                    $errorUserId = $aError['identifier'];
                }
            }
			if (!$hasError) {
				$allSended = true;
			} else {
                $commands[0][0] = '';
                $currentPid = getmypid();
                $shell = "ps -p $currentPid -o cmd=";
                $result = `$shell`;
                if ($result) {
                    preg_match_all('/Send.*Notification/i', $result, $commands);
                    $command0 = $commands[0][0];
                }
                $userId = $errorUserId;
                $status = $aError['status'];
                //$sql = "INSERT eUser VALUES (".$userId.", '".$status."', ".time().", '$command0')";
                //$command = Yii::app()->db->createCommand($sql);
                //$result = $command->execute();
			}
 
			$pushEngine->apnsClose();
        }
        return true;
    }
	
	public static function getFeedback($sandboxMode = true, $debug = false) {
	
		$pemPath 	= dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cert_and_key' . DIRECTORY_SEPARATOR;
		$sslPem 	= $pemPath . ($sandboxMode ? self::$sslPemName[1] : self::$sslPemName[0]);
		
		$feedbackEngine = new ApnStupid_Feedback($sandboxMode);
        $feedbackEngine->setSslPem($sslPem);
        $feedbackEngine->apnsConnect();
        $feedbacks = $feedbackEngine->receive();
        $feedbackEngine->apnsClose();
		return $feedbacks;
	}
}
?>
