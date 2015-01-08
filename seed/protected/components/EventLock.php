<?php
/**
 * 事件锁
 * 用法 ：$eventLock = new EventLock() ;
 *       
 *        $eventLock->getLock($name,$key) ;
 *        $eventLock->unlock($name,$key);
 */
class EventLock{
    private $_prefix = 'EventLock_';
    private $_timeout = 10;

    public function getLock($name, $key, $expire = 10){
        $lockCache = Yii::app()->cache;
        $waitTime = 0;
        if($lockCache->add($this->_prefix.$name.$key, @getmypid(), $expire)){
            return true;
        }
        return false;
    }
    
    public function waitLock($name, $key, $expire = 10){
        $lockCache = Yii::app()->cache;
        $waitTime = 0;
        while(!$lockCache->add($this->_prefix.$name.$key, @getmypid(), $expire)){
            if($waitTime < $this->_timeout){
                usleep(10000);
                $waitTime += 0.01;
            }else{
                return true;
            }
        }
        return false;
    }
    
    public function unlock($name, $key){
        $lockCache = Yii::app()->cache;
        $lockCache->delete($this->_prefix.$name.$key);
    }
}

