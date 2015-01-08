<?php
abstract class IncreasableRecord extends IncreasableParam
{
    private $_gateway;

    private $_value;
    private $_max;
    private $_changeValue;
    private $_refreshTime;

    public function __construct()
    {
        $this->_gateway = $this->_getGateway();

        foreach(array('value', 'max', 'changeValue', 'refreshTime') as $attribute){
            $name = $this->_getGatewayAttributeName($attribute);
            if(!property_exists($this->_gateway, $name)
                && !($this->_gateway->canGetProperty($name) && $this->_gateway->canSetProperty($name)))
            {
                throw new CException(Yii::t('application', "IncreasableParam's gateway {class} do not have property {name}.", array('{class}'=>get_class($this->_gateway), '{name}'=>$name)));
            }else{
                $attributeName = '_' . $attribute;
                $this->$attributeName = $this->_gateway->$name;
            }
        }
    }

    abstract protected function _getGateway();

    protected function _getGatewayAttributeNames(){
        return array();
    }

    private function _getGatewayAttributeName($attribute)
    {
        $attributeNames = $this->_getGatewayAttributeNames();
        if(isset($attributeNames[$attribute])){
            $attribute = $attributeNames[$attribute];
        }

        return $attribute;
    }

    public function update($value, $refreshTime)
    {
        $this->_setValue($value);
        $this->_setRefreshTime($refreshTime);
        $this->_saveAttributes(array('value', 'refreshTime'));

        //通知native更新相关数据
        if ($this->_gateway->type==PLAYERPOINT_TYPE_ACTIONPOINT) {
            GlobalState::set($this->_gateway->playerId, array('ACTION_POINT'=>floor($value), 'ACTION_POINT_TIME'=>$this->getRemainTime()));
        }
    }

    protected function _getValue()
    {
        return $this->_value;
    }

    public function getChangeValue($time=null)
    {
        return $this->_changeValue;
    }

    public function getMax()
    {
        return $this->_max;
    }

    public function getRefreshTime()
    {
        return $this->_refreshTime;
    }

    protected function _setValue($value)
    {
        $this->_value = $value;
        $name = $this->_getGatewayAttributeName('value');
        $this->_gateway->$name = $value;
    }

    protected function _setChangeValue($value)
    {
        $this->_changeValue = $value;
        $name = $this->_getGatewayAttributeName('changeValue');
        $this->_gateway->$name = $value;
    }

    protected function _setMax($value)
    {
        $this->_max = $value;
        $name = $this->_getGatewayAttributeName('max');
        $this->_gateway->$name = $value;
    }

    protected function _setRefreshTime($value)
    {
        $this->_refreshTime = $value;
        $name = $this->_getGatewayAttributeName('refreshTime');
        $this->_gateway->$name = $value;
    }

    protected function _saveAttributes($attributes)
    {
        $names = array();
        foreach($attributes as $attribute){
            $names[] = $this->_getGatewayAttributeName($attribute);
        }
        $this->_gateway->saveAttributes($names);
    }
}

