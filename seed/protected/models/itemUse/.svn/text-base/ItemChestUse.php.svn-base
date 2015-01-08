<?php

class ItemChestUse extends CBehavior {

    public function parseEffect($itemMeta, $item, $playerId) {
        $array = array();
        $item->useItem($itemMeta, 'Use Chest ,use playerId:' . $playerId . '');
        $itemObject = $itemMeta->itemObject;

        //宝箱逻辑覆盖innerItems  $itemId=>$number格式
        $configData = Util::loadConfig('ChestRate');
        $randIndex = mt_rand(1, 10000);
        $sumMax = 0;
        foreach ($configData as $r) {
            $sumMax += $r['rate'];
            if ($randIndex <= $sumMax) {
                break;
            }
        }

        switch ($r['type']) {
            case 'gold' :
                $player = Yii::app()->objectLoader->load('Player', $playerId);
                $value = $player->level*$r['count'];
                $player->addGold($value);
                $message = 'gold +' . $value;
                $array[0]['gold'] = 'gold';
                $array[0]['num'] = $value;
                break;
            case 'money' :
                $playerMoney = Yii::app()->objectLoader->load('PlayerMoney', $playerId);
                $playerMoney->send($r['count'], 'chest get');
                $message = 'money +' . $r['count'];
                $array[1]['money'] = 'money';
                $array[1]['num'] = $r['count'];
                break;
            case 'item' :
                $itemInnerMeta = Yii::app()->objectLoader->load('ItemMeta', $r['id']);
                $item->addItem($itemInnerMeta, 'Add Chest Inner Item', $r['count']);
                $message = 'item [' . $r['id'] . ']+' . $r['count'];
                $array[2]['id'] = $r['id'];
                $array[2]['num'] = $r['count'];
                $array[2]['name'] = $itemInnerMeta->getName();
                break;
            default :
                //do nothing
                $message = 'empty chest!';
                $array[3]['empty'] = 'empty';
                $array[3]['num'] = 0;
        }
        //Yii::app()->objectLoader->load('GlobalMessage', $playerId)->addMessage($message);
        //打开宝箱成就检查
        $achieveEvent = new AchievementEvent($playerId, ACHIEVEEVENT_TREASURE);
        $achieveEvent->onAchieveComplete();

        return $array;
    }

}
