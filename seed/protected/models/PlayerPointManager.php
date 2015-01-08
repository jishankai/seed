<?php
/**
 * PlayerPointManager
 *
 * @packaged default
 * @author Ji.Shankai
 **/
class PlayerPointManager extends CBehavior
{
    private $_playerId;
    private $_playerPoints;

    public function __construct($playerId)
    {
        $this->_playerId = $playerId;
        $this->_playerPoints = $this->defaultPlayerPoints();
    }

    public function defaultPlayerPoints()
    {
        return array(
            'actionPoint' => 'ActionPoint',
            'supplyPower' => 'SupplyPower',
        );
    }

    public function getPlayerPoints()
    {
        foreach ($this->_playerPoints as $name=>$playerPoint) {
            if (is_string($playerPoint)) {
                $this->_playerPoints[$name] = Yii::app()->objectLoader->load($playerPoint, $this->_playerId);
            }
        }

        return $this->_playerPoints;
    }

    public function initPlayerPoints()
    {
        foreach ($this->_playerPoints as $playerPoint) {
            $playerPoint::create($this->_playerId);
        }
    }

    public function getPlayerPoint($name)
    {
        if (isset($this->_playerPoints[$name])) {
            if (is_object($this->_playerPoints[$name])) {
                return $this->_playerPoints[$name];
            } else {
                if (is_string($this->_playerPoints[$name])) {
                    $this->_playerPoints[$name] = Yii::app()->objectLoader->load($this->_playerPoints[$name], $this->_playerId);
                    return $this->_playerPoints[$name];
                }elseif (is_array($this->_playerPoints[$name])) {
                    throw new CException(Yii::t('PlayerPointModel', 'Value of PlayerPoint {$name} not support.', array('{$name}'=>$name)));
                }else {
                    throw new CException(Yii::t('PlayerPointModel', 'Value of PlayerPoint {$name} not support.', array('{$name}'=>$name)));
                }
            }
        } else {
            throw new CException(Yii::t('PlayerPointModel', 'Do not have PlayerPoint {$name}.', array('{$name}'=>$name)));
        }
        
    }

    public function hasPlayerPoint($name)
    {
        if (isset($this->_playerPoints[$name])) {
            return true;
        } else {
            return false;
        }
    }

    public function addPlayerPoint()
    {
        // code...
    }

} // END class PlayerPointManager
?>
