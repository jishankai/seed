<?php

class ItemUseEffects extends CBehavior {

    public function parseEffect($itemMeta, $item, $seedId) {
        $seed = Yii::app()->objectLoader->load('Seed', $seedId);
            $item->useItem($itemMeta,'SeedEffect');
            $itemObject = $itemMeta->itemObject;
            if (isset($itemObject->effect['attributes']))
                $seed->addFeedAttributes($itemObject->effect['attributes']);
            if (isset($itemObject->effect['growValue'])) {
                $seed->setGrowValue($itemObject->effect['growValue']);
                //任务检查
                $missionEvent = new MissionEvent($seed->playerId, MISSIONEVENT_SEEDFEED);
                $missionEvent->onMissionComplete();
            }
    }

}
