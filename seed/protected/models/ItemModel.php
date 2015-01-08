<?php

class ItemModel {

    private $playerId;
    private $player;
    private $item;

    public function __construct($playerId) {
        $this->playerId = $playerId;
        $this->player = Yii::app()->objectLoader->load('Player', $this->playerId);
        $this->item = Yii::app()->objectLoader->load('Item', $this->playerId);
    }

    /*
     * 获取所有道具信息
     */

    public function getItemInfoAll() {
        $items = $this->item->getItems('decoItem');
        return $items;
    }

    public function getItemInfo($itemType, $category = -1) {
        if ($category == -1) {
            $items = $this->item->getItems($itemType);
        } else {
            $items = $this->item->getCItems($category, $itemType);
        }
        if ($items == false) {
            return array();
        } else {
            return $items;
        }
    }

    public function getCItemInfo($itemType, $category = -1) {
        if ($category == -1) {
            $items = $this->item->getItemsByPile($itemType);
        } else {
            $items = $this->item->getCItemsByPile($category, $itemType);
        }
        if ($items == false) {
            return array();
        } else {
            $rItems = array();
            $count = 0;
            //整合堆叠
            foreach ($items as $item) {
                for ($n = 1; $n <= $item['pile']; $n++) {
                    $count++;
                    $tempItem = $item;
                    $tempItem['pile'] = 1;
                    $tempItem['num'] = 0;
                    $rItems[$count] = $tempItem;
                }
                if ($item['num'] != 0) {
                    $count++;
                    $tempItem = $item;
                    $tempItem['pile'] = 0;
                    $rItems[$count] = $tempItem;
                }
            }
            return $rItems;
        }
    }

    public function getResItemInfo() {
        $items = $this->item->getItemsByPile('resItem');
        return $items;
    }

    public function getGrowItemInfo() {
        $items = $this->item->getItemsByPile('useItem', 7);
        return $items;
    }

    public function itemUse($itemId, $userId) {
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
        $checkItems = $itemMeta->checkItems($this->item, $userId);
        if ($checkItems) {
            return $itemMeta->setEffect($this->item, $userId);
        }
    }

    public function itemPresent($friendId, $itemId) {
        if (PlayerFriend::isFriend($this->playerId, $friendId) != 0) {
            throw new SException(Yii::t('Friend', 'you are not friends'));
        }
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
        $this->item->useItem($itemMeta, 'Present');
        $this->createPresentMailInfo($itemMeta, $friendId);
    }

    public function itemSell($itemId, $num = 1) {
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
        $this->item->useItem($itemMeta, 'Sell', $num);
        $this->player->addGold($itemMeta->itemObject->sellPrice * $num, GOLD_SELL);
    }

    public function createPresentMailInfo($itemMeta, $friendId) {
        $MailInfo = array(
            'playerId' => $friendId,
            'isGet' => MAIL_DEFAULTVALUE,
            'getDays' => MAIL_DEFAULTVALUE,
            'keepDays' => time() + 86400 * MAIL_MAXKEEPDAYS,
            'informType' => MAIL_PLAYERMAIL,
            'fromId' => $this->playerId,
            'content' => '',
            'goodsId' => $itemMeta->id,
            'seedId' => MAIL_DEFAULTVALUE,
            'sentGold' => MAIL_DEFAULTVALUE,
            'isRead' => MAIL_DEFAULTVALUE,
        );

        $arrayList = explode(',', $friendId);
        MailModel::givePresent($MailInfo, $arrayList);
    }

}

?>
