<?php

class ItemActionPointUse extends CBehavior {

    public function parseEffect($itemMeta, $item, $playerId) {
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        $item->useItem($itemMeta, 'Use ActionPoint Item ,use playerId:' . $playerId . '');
        $itemObject = $itemMeta->itemObject;
        if (isset($itemObject->effect['actionPoint'])) {
            $actionPoint = $itemObject->effect['actionPoint'];
            $maxPoint = $player->getPlayerPoint('actionPoint')->getMax();
            $addPoint = $maxPoint * $actionPoint;
            $player->getPlayerPoint('actionPoint')->addValue($addPoint);
        }
    }

}