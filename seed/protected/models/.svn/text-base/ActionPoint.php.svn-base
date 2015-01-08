<?php
/**
 * ActionPoint
 *
 * @packaged default
 * @author Ji.Shankai
 **/
class ActionPoint extends PlayerPointModel
{
    // 0-行动力
    protected  static $_type = PLAYERPOINT_TYPE_ACTIONPOINT;

    static protected function _getDefaults()
    {
        return array(
            'value' => ACTIONPOINT_VALUE,
            'changeValue' => ACTIONPOINT_CHANGEVALUE,
            'max' => ACTIONPOINT_MAX,
            'changeInterval' => ACTIONPOINT_CHANGEINTERVAL,
        );
    }

    public function getValue($time=null)
    {
        if($time === null){
            $time = time();
        }

        if($this->_isPaused()){
            return $this->_getValue();
        }

        if($time < $this->getRefreshTime()){
            $this->_logWrongRequestTime($time);
            $time = $this->getRefreshTime();
        }
        $value = $this->_getValue() + ($this->getChangeValue($time) * 
            intval(($time - $this->getRefreshTime()) / $this->getChangeInterval()));

        return min($value, $this->getMax());
    }

    public function getRemainTime()
    {
        return $this->_getCountTime($this->getRefreshTimestamp(), $this->getChangeInterval());
    }

    private function _getCountTime($refreshTime, $interval)
    {
        //If the refreshTime is in future, refresh in interval.
        if ($refreshTime>time()) {
            return $interval;
        } else {
            return $refreshTime+$interval-time();
        }
        
    }
} // END class ActionPoint
?>
