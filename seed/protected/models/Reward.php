<?php
abstract class Reward extends CComponent
{
    private $_name;
    private $_value;
    private $_from;
    private $_title;

    public function __construct($name, $value, $from=null, $title=null)
    {
        $this->_name = $name;
        $this->_value = $value;
        $this->_from = $from;
        $this->_title = $title;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function getFrom()
    {
        switch ($this->_from) {
            case 'MISSION':
                return '[%missionMail%]';
                break;
            case 'ACHIEVEMENT':
                return '[%achievementMail%]';
                break;
            case 'APPDRIVER':
                return '[%appDriverMail%]';
                break;
            default:
                return ;
                break;
        }
    }

    public function getTitle()
    {
        return $this->_title;
    }

    abstract public function reward($playerId);
}

