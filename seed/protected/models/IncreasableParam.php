<?php
/**
 * IncreasableParam is the base class for all objects whoes value may increase
 * with time and have a max.
 *
 */
abstract class IncreasableParam extends CComponent
{
    /**
     * Returns the changeValue value in interval.
     * @param integer $time the unix timestamp to get the changeValue.
     * @return integer the changeValue value in interval.
     * NOTE: This method is used to get value, so It should not have side
     * effect, that is, it shoud not update the underlying attributes such as
     * value or refreshTime.
     */
    abstract public function getChangeValue($time);

    abstract public function getMax();

    abstract public function getChangeInterval();

    abstract public function update($value, $refreshTime);
    
    abstract public function getRefreshTime();

    abstract protected function _getValue();

    abstract protected function _setValue($value);

    abstract protected function _setChangeValue($value);

    abstract protected function _setMax($value);

    abstract protected function _setRefreshTime($value);

    abstract protected function _saveAttributes($attributes);

    protected function _isPaused()
    {
        return false;
    }

    protected function _getMaxLimit()
    {
        return false;
    }

    public function _logWrongRequestTime($time)
    {
        $refreshTime = $this->getRefreshTime();
        $warning = "The request time($time) is earlier than refreshTime in db($refreshTime).";

        //More information about the wrong request time.
        if($time > time()){
            $warning .= "The request time is in future, this function may be called with wrong argument.";
        }elseif($time <= time() && $this->getRefreshTime() > time()){
            $warning .= "The refreshTime in db(" . $this->getRefreshTime() . ") is in future(May be caused by unsynchronized time setting between servers.";
        }else{
            $warning .= "All time are in past, the request may be delayed or called in wrong order.";
        }
        $warning .= 'Cast to refreshTime.';

        //Use Exception to get the trace.
        $exception = new Exception($warning);
        $log = $exception->__toString() . "\n";
        if(isset($_SERVER['REQUEST_URI'])){
            $log .= 'REQUEST_URI=' . $_SERVER['REQUEST_URI'];
        }
        Yii::log($log, CLogger::LEVEL_WARNING, 'application.' . get_called_class());
        //throw new CException(Yii::t('IncreasableParam', 'Can not decide the history changeValue value before refreshTime.'));
    }

    /**
     * Returns the value of increasable parameter at $time.
     * @param integer $time the unix timestamp to get the value. Use null to 
     * get value at now.
     * @return integer the value at $time.
     */
    public function getValue($time=null)
    {
        return;
    }

    /**
     * Return the time when the value should update(It may not actually increase if has 
     * reached the max value).
     * @param integer $time the time after last changeValue.
     * @return integer the time before $time that the value increased.
     * Note: When the value reached the max, update timestamp has no sense.
     */
    public function getRefreshTimestamp($time=null)
    {
        if($time === null){
            $time = time();
        }
        if($time < $this->getRefreshTime()){
            $this->_logWrongRequestTime($time);
            $time = $this->getRefreshTime();
        }

        return $this->getRefreshTime() + intval(($time - $this->getRefreshTime())/$this->getChangeInterval()) * $this->getChangeInterval();
    }

    /**
     * Manually add value.
     * @param integer $value the value to add.
     * @param integer $time the timestamp to add value. Use null to add value 
     * right now.
     * Note: 
     */
    public function addValue($value, $time=null)
    {
        if($value < 0){
            $this->subValue(0-$value, $time);
            return;
        }

        if($time === null){
            $time = time();
        }

        $value = $this->getValue($time) + $value;
        $value = min($value, $this->getMax());

        $refreshTime = $this->getRefreshTimestamp($time);
        
        $this->update($value, $refreshTime, true);

        //好友充能增加金钱用 
        return $value;
    }

    public function subValue($value, $time=null)
    {
        if($value < 0){
            $this->addValue(0-$value, $time);
            return;
        }
        if($time === null){
            $time = time();
        }

        $value = $this->getValue($time) - $value;
        if ($value<0) {
            if ($this->getType()==PLAYERPOINT_TYPE_ACTIONPOINT) {
                throw new SException('action point is not enough', EXCEPTION_TYPE_AP_NOT_ENOUGH); //行动力不足 
            }
        }

        if($this->getValue($time) == $this->getMax()){
            //The old value was stopped on max, restart update from the time of 
            //being substracted.
            $refreshTime = $time;
        }else{
            $refreshTime = $this->getRefreshTimestamp($time);
        }

        $this->update($value, $refreshTime);
    }

    /**
     * Alias for subValue.
     */
    public function useValue($value, $time=null)
    {
        $this->subValue($value, $time);
    }

    /**
     * Adds the max value.
     * @param integer the max value to be added.
     * @param integer the timestamp max be added. Use null to add max value 
     * right now.
     */
    public function addMax($maxValue, $time=null)
    {
        if($maxValue < 0){
            throw new CException(Yii::t('IncreasableParam', 'Value to add should be positive.'));
        }
        if($time === null){
            $time = time();
        }
        $max = $this->getMax() + $maxValue;

        if($this->_getMaxLimit() != false){
            if($max > $this->_getMaxLimit()){ 
                $max = $this->_getMaxLimit();
            }
        }

        if($this->getValue($time) >= $this->getMax()){
            //If the value has reached the old max, update the value and update time.
            $refreshTime = $time;
            $value = $this->getMax();

            $this->update($value, $refreshTime);
        }
        $this->_setMax($max);
        $this->_saveAttributes(array('max'));
    }

    /**
     * Subtract the max value
     * @param integer the max value to be substracted.
     * @param integer the timestamp max be substracted, use null to substract 
     * max value right now.
     */
    public function subMax($maxValue, $time=null)
    {
        if($maxValue < 0){
            throw new CException(Yii::t('IncreasableParam', 'Value to sub should be positive.'));
        }
        if($time === null){
            $time = time();
        }
        $max = $this->getMax() - $maxValue;
        if($this->getValue($time) >= $max){
            $refreshTime = $time;
            $value = $max;

            $this->update($value, $refreshTime);
        }
        $this->_setMax($max);
        $this->_saveAttributes(array('max'));
    }

    /**
     * Add changeValue value.
     * @param integer $changeValue the changeValue value to be added.
     * @param integer $startTime the timestamp changeValue increases, use null to 
     * increase right now.
     */
    public function addChangeValue($changeValue, $startTime=null)
    {
        if($startTime === null){
            $startTime = time();
        }

        $updateValue = $this->getValue($startTime);
        $refreshTime = $this->getRefreshTimestamp($startTime);


        $this->update($updateValue, $refreshTime);

        $this->_setChangeValue($this->getChangeValue($startTime) + $changeValue);
        $this->_saveAttributes(array('changeValue'));
    }

    public function subChangeValue($changeValue, $startTime=null)
    {
        $this->addChangeValue(0-$changeValue, $startTime);
    }

    /**
     * Pauses the increasing of parameter.
     */
    public function pause()
    {
        $this->update($this->getValue(), time());
    }

    /**
     * Resume the increasing of parameter.
     */
    public function resume()
    {
        $this->_setRefreshTime(time());
        $this->_saveAttributes(array('refreshTime'));
    }

    /**
     * Update the value data in database. There should be no changeValue change 
     * during last update time and the parameter time.
     * @param integer $time the time value should update to.
     */
    public function updateValue($time=null)
    {
        $this->addValue(0, $time);
    }

    /**
     * Restore value to max
     * @param integer the restore time, null means now.
     */
    public function restoreToMax($time=null)
    {
        if($time == null){
            $time = time();
        }

        $this->update($this->getMax(), $time);
    }
}

