<?php
/**
 * MultiResAchieveChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-08
 * @package Seed
 **/
class MultiResAchieveChecker extends AchieveGatherChecker
{
    private $_deserveResources;
    private $_count; //每一种需要多少数量

    function __construct($achievementId, $deserveResources)
    {
        $deserveResources = explode(',', $deserveResources);
        foreach ($deserveResources as $deserveResource) {
            $deserveResource = explode(':', $deserveResource);
            $this->_deserveResources[$deserveResource[0]] = $deserveResource[1];
            if (empty($this->_count)) {
                $this->_count = $deserveResource[1];
            }
        }
        parent::__construct($achievementId);
    }

    public function checkComplete($event)
    {
        //$currentProcess = $this->_setAchieveProcess($event->playerId);
        $currentProcess = $event->process;
        return $this->_checkAchieveProcess($currentProcess, $event->playerId);
    }

    protected function _checkAchieveProcess($currentProcess, $playerId)
    {
        //$currentResources = Item::getResNumArray($currentProcess);
        $currentResources = $currentProcess;
        $currentCount = 0;
        foreach ($currentResources as $value) {
            $currentCount += min($value, $this->_count);
        }
        $this->saveProcessCount($playerId, $currentCount); 
        
        foreach ($this->_deserveResources as $category=>$value) {
            if (!isset($currentResources[$category]) or $currentResources[$category]<$value) {
                return false;
            }
        }
        return true;    
    }
}
?>
