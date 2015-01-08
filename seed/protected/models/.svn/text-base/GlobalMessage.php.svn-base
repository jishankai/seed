<?php

class GlobalMessage extends Model {
    public $playerId ;
    public $messageData ;
    private $player ;

    private $messageTypes ;
    
    
    private $messageContent ;

    public function __construct($playerId){
        $this->playerId = $playerId ;
        $this->player = Yii::app()->objectLoader->load('Player',$playerId);
        $this->checkPlayer( $this->player );
        $this->setMessageData();

        $this->messageTypes = Util::loadConfig(__CLASS__);
    }

    public function checkPlayer($player) {
        if( !$player->isExists() ) {
            throw new CException('player not exists');
        }
    }

    public function addMessage( $content,$messageType=MESSAGE_TYPE_NORMAL,$params=array() ) {
        if( !isset($this->messageTypes[$messageType]) ) return ;
        $basicData = isset($this->messageData[$messageType])?$this->messageData[$messageType]:array();
        //$relatedQueueId = 0 ;
        $rowData = array_merge(
            array(
                'content'       => $content ,
                'showTime'      => time() ,
            ) ,
            $this->getTypeParams($messageType,$params) 
        );
        if( !empty($this->messageTypes[$messageType]) ) {
            if( isset($basicData[$content])&&$basicData[$content]['showTime']==$rowData['showTime'] ) {
                //不更新状态记录
                return true;
            }
            $basicData[$content] = $rowData ;
        }
        else {
            $basicData[] = $rowData;
        }
        $this->messageData[$messageType] = $basicData ;
        $this->saveMessageData();
    }

    public function removeMessage( $content,$messageType ) {
        if( isset($this->messageData[$messageType][$content]) ){
            unset($this->messageData[$messageType][$content]);
            $this->saveMessageData();
        }
    }

    public function getMessageData( $messageType=0 ) {
        $resultArray = array();
        $currentTime = time();
        $dataChanged = false ;
        $lastGetTime = Yii::app()->objectLoader->load('Player',$this->playerId)->getStatus('globalMessageTime');
        if( empty($lastGetTime) ) $lastGetTime = $currentTime ;
        foreach( $this->messageData as $type=>$data ){
            if( !empty($messageType)&&$type!=$messageType ) {
                continue ;
            }
            else {
                /** 不存在的类别移除 **/
                if( empty($this->messageTypes[$type]) ) {
                    unset( $this->messageData[$type] );
                    $dataChanged = true ;
                }
                foreach( $data as $i=>$r ) {
                    if( $r['showTime']>$currentTime ) {
                        continue ;
                    }
                    else {
                        /** 展示后即移除消息 **/
                        if( !empty($this->messageTypes[$type]['showRemove']) ) {
                            unset( $this->messageData[$type][$i] );
                            $dataChanged = true ;
                        }
                        /** 过期消息自动移除 **/
                        if( !empty($this->messageTypes[$type]['overRemove'])&&!empty($r['removeTime'])&&$r['removeTime']<$currentTime ) {
                            unset( $this->messageData[$type][$i] );
                            $dataChanged = true ;
                            continue ;
                        }
                        /** 显示时间超过设定时间后移除 **/
                        if( !empty($this->messageTypes[$type]['removeTime'])&&$currentTime-$r['showTime']>$this->messageTypes[$type]['removeTime'] ) {
                            unset( $this->messageData[$type][$i] );
                            $dataChanged = true ;
                            continue ;
                        }

                        if( !empty($this->messageTypes[$type]['gapTime'])&&$currentTime-$lastGetTime<$this->messageTypes[$type]['gapTime'] ) {
                            unset( $this->messageData[$type][$i] );
                            $dataChanged = true ;
                            continue ;
                        }
                        
                        if( !empty($this->messageTypes[$type]['content']) ) {
                            if( empty($resultArray[$type]) ) {
                                $params = array(
                                    '{count}'=>count($data) , 
                                );
                                foreach( $this->messageTypes[$type]['params'] as $key ) {
                                    if( !isset($r[$key]) )  continue ;
                                    $params['{'.$key.'}'] = $r[$key] ;
                                }
                                $resultArray[$type][] = Yii::t('Message',$this->messageTypes[$type]['content'],$params) ;
                            }
                        }
                        else {
                            $resultArray[$type][] = $r['content'] ;
                        }
                    }
                }
            }
        }
        if( $dataChanged ) {
            $this->saveMessageData() ;
            Yii::app()->objectLoader->load('Player',$this->playerId)->setStatus('globalMessageTime',$currentTime);
            //var_dump($resultArray); exit;
        }
        return $resultArray ;
    }



    public function saveMessageData() {
        $this->player->messageData = serialize( $this->messageData );
        //var_dump($this->messageData,$this->player->messageData);
        $this->player->saveAttributes( array('messageData') );
    }

    private function setMessageData() {
        /*
        $cacheData = $this->cache($this->playerId);
        if( empty($cacheData) ) {
            $cacheData = !empty($this->player->messageData)?unserialize($this->player->messageData):array();
            $this->cache( $this->playerId,$cacheData );
        }*/
        $cacheData = !empty($this->player->messageData)?@unserialize($this->player->messageData):array();
        $this->messageData = $cacheData ;
    }
    
    private function getTypeParams( $messageType,$params ) {
        if( !isset( $this->messageTypes[$messageType]['params'] ) ) {
            return array();
        }
        else {
            $array = array() ;
            foreach( $this->messageTypes[$messageType]['params'] as $key ) {
                if( !isset($params[$key]) ) {
                    continue ;
                }
                $array[$key] = $params[$key] ;
            }
        }
        return $array ;
    }

    private function getLastTime() {
    }



    /*********************************************
     * 外部直接调用接口
     *********************************************
     */



    /**
     * 太阳能不足提示
     */
    public static function setPowerWarning( $playerId ) {
        $player = Yii::app()->objectLoader->load('Player',$playerId);
        $supplyPower = $player->getPlayerPoint('supplyPower');
        $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$playerId);
        $warningMinute = 10 ; //分钟 来源于用户配置
        $gapTime = $supplyPower->getRemainTime()-$warningMinute*60 ;
        if( $gapTime<0 ) {
            return false;
        }
        $params = array(
            'showTime'  => time()+$gapTime ,
            'warningMinute' => $warningMinute ,
        );
        $globalMessage -> addMessage( 1,MESSAGE_TYPE_POWER_WARNING,$params );
    }

    public static function addNewMission(  ) {
        
    }

    public static function removeMailMessage( $playerId ) {
        $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$playerId);
        if( !empty($globalMessage->messageData[MESSAGE_TYPE_NEW_MAIL]) ) {
            $globalMessage->messageData[MESSAGE_TYPE_NEW_MAIL] = array() ;
            $globalMessage->saveMessageData() ;
        }
    }

    public static function removeMissionMessage( $playerId ) {
        $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$playerId);
        if( !empty($globalMessage->messageData[MESSAGE_TYPE_NEW_MISSION]) ) {
            $globalMessage->messageData[MESSAGE_TYPE_NEW_MISSION] = array() ;
            $globalMessage->saveMessageData() ;
        }
    }

    public static function addMailGift( $mailId ) {
        $mail = Yii::app()->objectLoader->load('Mail',$mailId);
        if( $mail->informType==MAIL_PLAYERMAIL && $mail->getDays>0 ) {
            $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$mail->playerId);
            $params = array(
                'showTime'  => $mail->getDays ,
                'removeTime'=> $mail->keepDays ,
            );
            $globalMessage -> addMessage($mailId,MESSAGE_TYPE_MAIL_GIFT,$params);
        }
        return true;
    }

    public static function addLevelUp( $playerId,$level ) {
        $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$playerId);
        $params = array(
            'level' => $level ,
        );
        $globalMessage -> addMessage($level,MESSAGE_TYPE_LEVEL_UP,$params);
    }

}

