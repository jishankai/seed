<?php

class Item extends RecordModel {

    public $playerId;
    private $_items;
    private $_item;
    private $_owner;
    private $_enabled = true;
    static private $_columns = array('playerId', 'decoItem', 'resItem', 'useItem', 'chestItem', 'cupState');

    public function checkOwner($playerId) {
        if (!$this->isOwner($playerId)) {
            throw new CException(Yii::t('Item', 'the Item not belong you'));
        }
    }

    public function isOwner($playerId) {
        return ($this->playerId == $playerId) ? true : false;
    }

    public function __construct($playerId) {
        $this->playerId = $playerId;
        $this->attachBehavior('DecorationManager', new DecorationManager);
    }

    public function getPlayer() {
        return Yii::app()->objectLoader->load('Player', $this->_playerId);
    }

    public static function attributeColumns() {
        return self::$_columns;
    }

    protected function loadData() {
        $command = Yii::app()->db->createCommand('SELECT * FROM item WHERE playerId=:playerId');
        $rowData = $command->bindParam(':playerId', $this->playerId)->queryRow();
        return $rowData;
    }

    protected function saveData($attributes = array()) {
        return DbUtil::update(Yii::app()->db, 'item', $attributes, array('playerId' => $this->playerId));
    }

    public static function multiLoad($params = array(), $isSimple = true) {
        $sql = "SELECT * FROM item";
        if (!empty($params)) {
            $sql .= ' WHERE ' . implode(' AND ', $params);
        }
        return self::multiLoadBySql($sql, 'id', array(), $isSimple);
    }

    public static function create($createInfo) {
        $insertArr = array();
        foreach (self::attributeColumns() as $key) {
            if (isset($createInfo[$key])) {
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['createTime'] = time();
        return DbUtil::insert(Yii::app()->db, 'item', $insertArr, true);
    }

    private function addPlayerData() {
        $data = array(
            'playerId' => $this->playerId,
            'decoItem' => '',
            'resItem' => '',
            'useItem' => '',
            'chestItem' => '',
        );
        item::create($data);
    }

    public static function initItem($playerId) {
        $data = array(
            'playerId' => $playerId,
            'decoItem' => '',
            'resItem' => '',
            'useItem' => '',
            'chestItem' => '',
        );
        item::create($data);
    }

    private function DelPlayerData() {
        $command = Yii::app()->db->createCommand("DELETE FROM item WHERE playerId = :playerId");
        $command->bindParam(':playerId', $this->playerId);
        return $command->execute();
    }

    private function getPlayerDataCount() {
        $command = Yii::app()->db->createCommand("SELECT * FROM item WHERE playerId = :playerId");
        $command->bindParam(':playerId', $this->playerId);
        return $command->queryColumn();
    }

    private function getItemArray($type) {
        if (isset($this->$type)) {
            return unserialize($this->$type);
        } else {
            return array();
        }
    }

    static private function getClassByType($type) {
        switch ($type) {
            case 'decoItem':
            case 'resItem':
            case 'useItem':
            case 'chestItem':
            case 'itemMeta':
                return ucfirst($type);
        }
    }

    static private function getTypeByClass($class) {
        if (is_object($class)) {
            $class = get_class($class);
        }
        switch ($class) {
            case 'DecoItem':
            case 'ResItem':
            case 'UseItem':
            case 'ChestItem':
            case 'ItemMeta':
                return lcfirst($class);
        }
    }

    public function getItem($id) {
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $id);
        $type = $itemMeta->itemType;
        if (!isset($this->_item[$type])) {
            $itemsArray = $this->getItemArray($type);
            if ($itemsArray == false)
                return false;
            if (isset($itemsArray[$id])) {
                $item = $itemMeta->itemObject;
                $pile = (int) ($itemsArray[$i] / ITEM_MAXPILE);
                $num = $itemsArray[$i] % ITEM_MAXPILE;
                $this->_items[$type][$i] = array('item' => $item, 'pile' => $pile, 'num' => $num);
            }
        }
        return $this->_item[$type];
    }

    public function getItemsByPile($type, $minType = 0) {
        if (!isset($this->_items[$type])) {
            $itemsArray = $this->getItemArray($type);
            if ($itemsArray == false)
                return array();
            $itemsArray = $this->delZeroItem($itemsArray);
            $class = self::getClassByType($type);
            foreach ($itemsArray as $i => $arr) {
                if ($itemsArray[$i] > 0) {
                    $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $i);
                    $item = Yii::app()->objectLoader->load($class, $i);
                    if ($minType == 0) {
                        $pile = (int) ($itemsArray[$i] / ITEM_MAXPILE);
                        $num = $itemsArray[$i] % ITEM_MAXPILE;
                        $this->_items[$type][$i] = array('item' => $item, 'pile' => $pile, 'num' => $num, 'itemMeta' => $itemMeta);
                    } else if ($minType != 0 && $item->type == $minType) {
                        $pile = (int) ($itemsArray[$i] / ITEM_MAXPILE);
                        $num = $itemsArray[$i] % ITEM_MAXPILE;
                        $this->_items[$type][$i] = array('item' => $item, 'pile' => $pile, 'num' => $num, 'itemMeta' => $itemMeta);
                    }
                }
            }
        }
        return $this->_items[$type];
    }

    public function getCItemsByPile($category, $type, $minType = 0) {
        if (!isset($this->_items[$type])) {
            $itemsArray = $this->getItemArray($type);
            if ($itemsArray == false)
                return array();
            $itemsArray = $this->delZeroItem($itemsArray);
            $class = self::getClassByType($type);
            foreach ($itemsArray as $i => $arr) {
                $item = Yii::app()->objectLoader->load($class, $i);
                if ($item->category == $category) {
                    if ($itemsArray[$i] > 0) {
                        $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $i);
                        $item = Yii::app()->objectLoader->load($class, $i);
                        if ($minType == 0) {
                            $pile = (int) ($itemsArray[$i] / ITEM_MAXPILE);
                            $num = $itemsArray[$i] % ITEM_MAXPILE;
                            $this->_items[$type][$i] = array('item' => $item, 'pile' => $pile, 'num' => $num, 'itemMeta' => $itemMeta);
                        } elseif ($minType != 0 && $item->type == $minType) {
                            $pile = (int) ($itemsArray[$i] / ITEM_MAXPILE);
                            $num = $itemsArray[$i] % ITEM_MAXPILE;
                            $this->_items[$type][$i] = array('item' => $item, 'pile' => $pile, 'num' => $num, 'itemMeta' => $itemMeta);
                        }
                    }
                }
            }
        }
        return $this->_items[$type];
    }

    public function getItems($type, $minType = 0) {
        if (!isset($this->_items[$type])) {
            $itemsArray = $this->getItemArray($type);
            if ($itemsArray == false)
                return array();
            $itemsArray = $this->delZeroItem($itemsArray);
            $class = self::getClassByType($type);
            foreach ($itemsArray as $i => $arr) {
                $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $i);
                $item = Yii::app()->objectLoader->load($class, $i);
                if ($minType == 0) {
                    $this->_items[$type][$i] = array('item' => $item, 'num' => $itemsArray[$i], 'itemMeta' => $itemMeta);
                } elseif ($minType != 0 && $item->type == $minType) {
                    $this->_items[$type][$i] = array('item' => $item, 'num' => $itemsArray[$i], 'itemMeta' => $itemMeta);
                }
            }
        }
        return $this->_items[$type];
    }

    public function getCItems($category, $type, $minType = 0) {
        if (!isset($this->_items[$type])) {
            $itemsArray = $this->getItemArray($type);
            if ($itemsArray == false)
                return array();
            $itemsArray = $this->delZeroItem($itemsArray);
            $class = self::getClassByType($type);
            foreach ($itemsArray as $i => $arr) {
                $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $i);
                $item = Yii::app()->objectLoader->load($class, $i);
                if ($item->category == $category) {
                    if ($minType == 0) {
                        $this->_items[$type][$i] = array('item' => $item, 'num' => $itemsArray[$i], 'itemMeta' => $itemMeta);
                    } elseif ($minType != 0 && $item->type == $minType) {
                        $this->_items[$type][$i] = array('item' => $item, 'num' => $itemsArray[$i], 'itemMeta' => $itemMeta);
                    }
                }
            }
        }
        return $this->_items[$type];
    }

    public function delZeroItem($itemsArray) {
        foreach ($itemsArray as $itemId => $arr) {
            if ($itemsArray[$itemId] == 0) {
                $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
                if ($itemMeta->getCategory() != 5) {
                    unset($itemsArray[$itemId]);
                }
            }
        }

        ksort($itemsArray);

        return $itemsArray;
    }

    public static function makeItem($itemId) {
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
        if ($itemMeta->isNew()) {
            throw new SException(Yii::t('Item', "item {itemId} not defined.", array('{itemId}' => $itemId)));
        } else {
            $class = self::getClassByType($itemMeta->type);
            return new $class($itemId);
        }
    }

    public function getDecoItem() {
        $decoItem = unserialize($this->decoItem);
        if (empty($decoItem)) {
            $decoItem = array();
        }
        return $decoItem;
    }

    public function setDecoItem($decoItem) {
        $this->decoItem = serialize($decoItem);
    }

    public function getCupState() {
        $array = unserialize($this->cupState);
        return $array;
    }

    public function setCupState($array) {
        $this->cupState = serialize($array);
        $this->saveAttributes(array('cupState'));
    }

    public function changeCupState($id, $state) {
        $array = $this->getCupState();
        $array[$id] = $state;
        $this->setCupState($array);
    }

    public function addItem($item, $addFrom = '', $num = 1, $ignoreFull = false) {
        $row = $this->getPlayerDataCount();
        if (empty($row[0])) {
            throw new CException(Yii::t('Item', 'you player is not in db'));
        }
        $type = self::getTypeByClass($item);
        $itemId = $item->id;
        if ($type == 'itemMeta') {
            $type = lcfirst($item->itemType);

            if ($item->category == 6) {
                $itemObject = $item->itemObject;
                $gold = $itemObject->effect['gold'] * $num;
                $Player = Yii::app()->objectLoader->load('Player', $this->playerId);
                $Player->addGold($gold);
                return true;
            }
        }
        $itemArray = $this->getItemArray($type);
        if ($type == 'resItem' || $type == 'useItem' || $type == 'chestItem') {
            $surplusPosition = $this->getSurplusPosition($item);
            if ($surplusPosition < $num) {
                if ($ignoreFull) {
                    Yii::app()->objectLoader->load('GlobalMessage', $this->playerId)->addMessage($type, MESSAGE_TYPE_ITEM_FULL);
                    return false;
                } else {
                    throw new SException($type, EXCEPTION_TYPE_ITEM_FULL);
                }
            }
        }
        if (isset($itemArray[$itemId])) {
            $itemArray[$itemId] = $itemArray[$itemId] + $num;
        } else {
            $itemArray[$itemId] = $num;
        }

        $this->$type = serialize($itemArray);
        $this->saveAttributes(array($type));
        $desc = 'add a item to item_Table, id is' . $item->id . ', add num is ' . $num . ', add from ' . $addFrom . ', playerId is' . $this->playerId . '.';
        $this->saveLog($item->id, $num, 'add', $desc, $this->playerId);

        //仓库饱满成就检查
        $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_STOREFULL);
        $achieveEvent->onAchieveComplete();

        //成就检查
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
        if ($itemMeta->getItemType()=='ResItem') {
            $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_RESITEM);
            $achieveEvent->saveProcess(array($itemMeta->getType() => $num));
            $achieveEvent->onAchieveComplete();
            $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_USEITEM);
            $achieveEvent->saveProcess($itemId);
            $achieveEvent->onAchieveComplete();
        } else if ($itemMeta->getItemType()=='UseItem') {
            $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_USEITEM);
            $achieveEvent->saveProcess($itemId);
            $achieveEvent->onAchieveComplete();
        } else if ($itemMeta->getItemType()=='DecoItem') {
            if ($itemMeta->getCategory()==4) {
                $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_BACKGROUND);
                $achieveEvent->saveProcess($itemId);
                $achieveEvent->onAchieveComplete();
            } else {
                $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_DECOITEM);
                $achieveEvent->saveProcess($itemId);
                $achieveEvent->onAchieveComplete();
            }
        }

        return true;
    }

    public function isPileFullByType($type) {
        $itemArray = $this->getItemArray($type);
        if ($itemArray == false) {
            return false;
        }
        $pileCount = 0;
        foreach ($itemArray as $arr) {
            $pile = 0;
            $pile = (int) ($arr / ITEM_MAXPILE);
            $num = $arr % ITEM_MAXPILE;
            if ($num > 0) {
                $pile = $pile + 1;
            }
            $pileCount = $pileCount + $pile;
        }

        if ((ITEM_MAXPILENUM - $pileCount) > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function isPileFullAll() {
        $bool = false;
        $array = array('resItem', 'useItem', 'chestItem');
        foreach ($array as $arr) {
            $bool = $bool || $this->isPileFullByType($arr);
        }
        return $bool;
    }

    public function isPileFull($array = array()) {
        $bool = true;
        if (empty($array)) {
            $array = array('resItem',);
        }
        foreach ($array as $arr) {
            $bool = $bool & $this->isPileFullByType($arr);
        }
        return $bool;
    }

    public function getSurplusPosition($item) {
        $SurplusPosition = 0;
        $type = self::getTypeByClass($item);
        $itemId = $item->id;
        if ($type == 'itemMeta') {
            $type = lcfirst($item->itemType);
        }
        $itemArray = $this->getItemArray($type);
        if ($itemArray == false) {
            return ITEM_MAXPILENUM;
        }
        if ($type == 'resItem' || $type == 'useItem' || $type == 'chestItem') {
            $maxPile = $this->getMaxPile($itemArray, $itemId);
            $SurplusPile = ITEM_MAXPILENUM - $maxPile;
            $nums = 0;
            if (isset($itemArray[$itemId])) {
                $nums = $itemArray[$itemId] % ITEM_MAXPILE;
            }
            if ($nums > 0) {
                $SurplusPosition = $SurplusPile * ITEM_MAXPILE;
                $SurplusPosition = $SurplusPosition + (ITEM_MAXPILE - $nums);
            } else {
                $SurplusPosition = $SurplusPile * ITEM_MAXPILE;
            }
        }
        return $SurplusPosition;
    }

    public function getMaxPile($itemArray, $itemId, $num = 0) {
        if (isset($itemArray[$itemId])) {
            $itemArray[$itemId] = $itemArray[$itemId] + $num;
        } else {
            $itemArray[$itemId] = $num;
        }
        $pileCount = 0;
        foreach ($itemArray as $arr) {
            $pile = 0;
            $pile = (int) ($arr / ITEM_MAXPILE);
            $num = $arr % ITEM_MAXPILE;
            if ($num > 0) {
                $pile = $pile + 1;
            }
            $pileCount = $pileCount + $pile;
        }
        return $pileCount;
    }

    public function useItem($item, $useTo = '', $num = 1, $flag = false) {
        $row = $this->getPlayerDataCount();
        if (empty($row[0])) {
            throw new CException(Yii::t('Item', 'you player is not in db'));
        }
        $type = self::getTypeByClass($item);
        $itemId = $item->id;
        if ($type == 'itemMeta') {
            $type = lcfirst($item->itemType);
        }
        $itemArray = $this->getItemArray($type);
        if (isset($itemArray[$itemId])) {
            if ($itemArray[$itemId] > $num) {
                $itemArray[$itemId] -= $num;
            } elseif ($itemArray[$itemId] == $num) {
                $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
                //奖杯控制器
                if ($itemMeta->getCategory() != 5 || $flag == true) {
                    unset($itemArray[$itemId]);
                } else {
                    $itemArray[$itemId] -= $num;
                }
            } else {
                throw new CException(Yii::t('Item', 'not enough item'));
            }
        } else {
            $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
            //奖杯控制器
            if ($itemMeta->getCategory() != 5) {
                throw new CException(Yii::t('Item', 'you have no item'));
            }
        }
        $this->$type = serialize($itemArray);
        $this->saveAttributes(array($type));
        $desc = 'use a item from item_Table, id is' . $item->id . ', add num is ' . $num . ', use to ' . $useTo . ', playerId is' . $this->playerId . '.';
        $this->saveLog($item->id, $num, 'use', $desc, $this->playerId);
    }

    public function saveLog($id, $num, $actionType, $desc, $playerId = 0) {
        if (empty($playerId)) {
            $playerId = $this->playerId;
        }
        return ItemActionLog::save($id, $num, $actionType, $desc, $playerId);
    }

    public function getItemNum($itemIds) {
        $count = 0;
        if (empty($itemIds)) {
            return $count;
        }
        foreach ($itemIds as $itemId) {
            $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
            $itemType = lcfirst($itemMeta->getItemType());
            $itemArray = $this->getItemArray($itemType);

            if ($itemArray != false) {
                foreach ($itemArray as $Id => $num) {
                    if ($itemId == $Id) {
                        $count = $count + $num;
                    }
                }
            }
        }

        return $count;
    }

    public function attach($owner) {
        $this->_owner = $owner;
    }

    //    public function detach($owner) {
    //        $this->_owner = null;
    //    }

    public function getEnabled() {
        return $this->_enabled;
    }

    public function setEnabled($value) {
        $this->_enabled = $value;
    }

    public static function getCountByItemId($itemArray, $itemType, $category = 0) {
        $count = 0;
        $list = array();

        if (empty($itemArray)) {
            return $count;
        }
        foreach ($itemArray as $itemArr) {
            foreach ($itemArr as $id => $num) {
                $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $id);
                if ($itemMeta->itemType == $itemType) {
                    $item = $itemMeta->itemObject;
                    if ($category == 0 || $item->category == $category) {
                        array_push($list, $id);
                    }
                }
            }
        }
        $list = array_unique($list);
        $count = count($list);
        return $count;
    }

    public static function getResNumArray($itemArray) {
        $newArray = array();

        if (empty($itemArray)) {
            for ($i = 1; $i <= ITEM_FEEDATTRIBUTEMAXID; $i++) {
                $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $i);
                $item = $itemMeta->itemObject;
                $type = $item->type;
                if (isset($newArray[$type])) {
                    $newArray[$type] = 0;
                }
            }
        }
        foreach ($itemArray as $itemArr) {
            foreach ($itemArr as $id => $num) {
                if ($id <= ITEM_FEEDATTRIBUTEMAXID) {
                    $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $id);
                    $item = $itemMeta->itemObject;
                    $type = $item->type;
                    if (isset($newArray[$type])) {
                        $newArray[$type] = $newArray[$type] + $num;
                    } else {
                        $newArray[$type] = $num;
                    }
                }
            }
        }

        return $newArray;
    }

}
