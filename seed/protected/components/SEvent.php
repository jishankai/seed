<?php
class SEvent
{
    public $playerId;
    public $eventId;
    public $params;
    
    function __construct($playerId, $eventId, $params=array()) {
        $this->playerId = $playerId;
        $this->eventId = $eventId;
        $this->params = $params;
    }
}
?>
