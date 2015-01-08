<?php

class ItemMaterialBagUse extends CBehavior {

    public function parseEffect($itemMeta, $item, $playerId) {
        $array = array();
        $item->useItem($itemMeta, 'Use MaterialBag ,use playerId:' . $playerId . '');
        $itemObject = $itemMeta->itemObject;
        if (isset($itemObject->effect['innerItems'])) {
            $innerItems = $itemObject->effect['innerItems'];
            foreach ($innerItems as $itemId => $num) {
                $array[$itemId] = $num;
                $resItem = Yii::app()->objectLoader->load('ResItem', $itemId);
                $item->addItem($resItem, 'Add MaterialBag Inner Item', $num);
            }
        }
        return $array;
    }

}