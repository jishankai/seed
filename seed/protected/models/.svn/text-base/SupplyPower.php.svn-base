<?php
/**
 * SupplyPower
 *
 * @packaged default
 * @author Ji.Shankai
 **/
class SupplyPower extends PlayerPointModel
{
    // 1-太阳能
    protected static $_type = PLAYERPOINT_TYPE_SUPPLYPOWER;

    static protected function _getDefaults()
    {
        return array(
            'value' => SUPPLYPOWER_VALUE,
            'changeValue' => SUPPLYPOWER_CHANGEVALUE,
            'max' => SUPPLYPOWER_MAX,
            'changeInterval' => SUPPLYPOWER_CHANGEINTERVAL,
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
        
        $value = $this->_getValue() - $this->getChangeValue()/60 * 
            intval(($time - $this->getRefreshTime()) / $this->getChangeInterval());
        if ($value<=0) {
            $timeToZero = $this->getRefreshTime() + intval($this->_getValue() * $this->getChangeInterval() / ($this->getChangeValue()/60));
            $this->update(0, $timeToZero);
        }

        return max($value, 0);
    }

    public function getPercent()
    {
        return floor($this->getValue()/$this->getMax()*100);
    }

    public function getRemainTime($time=null)
    {
        if ($time==null) { 
            $remainTime = $this->getValue()*3600/$this->getChangeValue();
        } else {
            $time = max($this->getRefreshTime(), $time);
            if ($this->_getValue()*3600/$this->getChangeValue()>(time()-$time)) {
                $remainTime = time()-$time;
            } else {
                $remainTime = $this->_getValue()*3600/$this->getChangeValue();
            }
            
        }
        return floor($remainTime);        
    }

    public function getMaxTime()
    {
        return floor($this->getMax()*3600/$this->getChangeValue());
    }

    public function update($value, $refreshTime, $forced=false)
    {
        static $isUpdating = false;
        if ($isUpdating && !$forced) {
            return ;
        }
        $isUpdating = true;
        $seedModel = Yii::app()->objectLoader->load('SeedModel', $this->getPlayerId());
        $seedModel->updateSeedGrowValue();
        parent::update($value, $refreshTime);
        GlobalState::set($this->getPlayerId(), array('SUPPLY_POWER'=>$this->getRemainTime()));
    }
} // END class SupplyPower
?>
