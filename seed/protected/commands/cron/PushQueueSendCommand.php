<?php
class PushQueueSendCommand extends CConsoleCommand {
    private function usage() {
        echo "Usage: PushQueueSend start\n";
    }
    
    private function start() {
        $count = 0 ;
        $eventKey = __CLASS__;
        $eventName = __METHOD__;
        $event = new EventLock();
        while( $count<1000 ) {
            if( !$event->getLock($eventKey,$eventName) ) {
                return ;
            }
            $changedQueue = array();
            $pushNotifications = array();
            foreach( PushQueue::getDealQueue() as $q ) {
                $count ++ ;
                if( $q->getLock()&&$q->state==0 ) {
                    $player = Yii::app()->objectLoader->load( 'Player',$q->playerId );
                    $user = Yii::app()->objectLoader->load( 'User',$player->userId );
                    $token = $user->deviceId;
                    $content = array( 'alert'=>$q->content );

                    $pushNotifications[] = array(
                        'userid'    => $user->userId,
                        'token'     => $user->token ,
                        'content'   => array( 'aps'=>$content ),
                    );
                    $changedQueue[$count] = $q ;
                    //echo "[$count] {$user->token} $result \n";
                }
                else {
                    continue ;
                }
            }
            try {
                $result = APNS::pushNotification($pushNotifications, APPLE_NOTIFICATION_SANDBOX, APPLE_NOTIFICATION_DEBUG_MODE);
                foreach( $changedQueue as $q ) {
                    $q->setState();
                }
                $event->unlock($eventKey,$eventName);
            }
            catch(Exception $e) {
                $event->unlock($eventKey,$eventName);
                throw $e ;
            }
            sleep(1);
        }
    }
    
    public function run($args) {
        if(isset($args[0]) && $args[0] == 'start'){
            $this->start();
        }else{
            return $this->usage();
        }
    }
    
}
?>