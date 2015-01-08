<?php
/**
 * ItemCountAchieveChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-05
 * @package Seed
 **/
class ItemCountAchieveChecker extends AchieveGatherChecker
{
    private $_deserveParams;

    function __construct($achievementId, $deserveParams)
    {
        $this->_deserveParams = $deserveParams;
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
        $currentCount = 0;
        $deserveCount = 0;
        $currentCount += count($currentProcess);
        foreach (explode(';', $this->_deserveParams) as $deserveDatas) {
            $deserveData = explode(',', $deserveDatas);
            /*
            if (isset($deserveData[2])) {
                $currentCount += Item::getCountByItemId($currentProcess, $deserveData[0], $deserveData[2]);
            } else {
                $currentCount += Item::getCountByItemId($currentProcess, $deserveData[0]);
            }
             */
            $deserveCount += $deserveData[1];
        }


        $this->saveProcessCount($playerId, $currentCount);
        if ($currentCount>=$deserveCount) {
            return true;
        } else {
            return false;
        }

    }
}
?>
