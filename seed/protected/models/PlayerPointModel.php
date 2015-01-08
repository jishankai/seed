<?php
class PlayerPointModel extends IncreasableRecord
{
    protected static $_type;

    private $_gateway;
    private $_playerId;

    public function __construct($playerId){
        $this->_playerId = $playerId;
        $this->_gateway = PlayerPoint::loadDataByModel($playerId, static::$_type);
        parent::__construct();
    }

    protected function _getGateway(){
        return $this->_gateway;
    }

    public function getPlayerId()
    {
        return $this->_playerId;
    }

    /**
     * Returns the interval of value increase(in seconds).
     */
    public function getChangeInterval()
    {
        return $this->_gateway->changeInterval;
    }

    static protected function _getDefaults()
    {
        return array(
        );
    }

    static public function create($playerId)
    { 
        $columns = array(
            'playerId' => $playerId,
            'type' => static::$_type,
        );
        $defaults = static::_getDefaults();
        foreach(array('value', 'changeValue', 'max', 'changeInterval') as $attr){
            $columns[$attr] = $defaults[$attr];
        }
        $columns['refreshTime'] = $columns['createTime'] = time();

        PlayerPoint::create($columns);
    }

    public function setValue($value)
    {
        $this->_gateway->value += $value; 
        return $this->_gateway->saveAttributes(array('value'));
    }

    public function getType()
    {
        return static::$_type;
    }
}
