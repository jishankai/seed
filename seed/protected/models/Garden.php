<?php

class Garden extends RecordModel {

    public $gardenId;
    public $seedArrayList;

    public function __construct($gardenId) {
        $this->gardenId = $gardenId;
        $this->attachBehavior('DecorationManager', new DecorationManager);
        $this->attachBehavior('GardenDecoAttributes', new GardenDecoAttributes);
    }

    public function checkOwner($playerId) {
        if (!$this->isOwner($playerId)) {
            throw new CException(Yii::t('Garden', 'the garden not belong you'));
        }
    }

    public function isOwner($playerId) {
        return ($this->playerId == $playerId) ? true : false;
    }

    public static function attributeColumns() {
        return array(
            'gardenId', 'playerId', 'backGround', 'seedList', 'fosterList', 'seedCount', 'decoExtraGrow', 'decorationInfo', 'favouriteFlag', 'gardenSign'
        );
    }

    protected function loadData() {
        $command = Yii::app()->db->createCommand('SELECT * FROM garden WHERE gardenId=:gardenId');
        $rowData = $command->bindParam(':gardenId', $this->gardenId)->queryRow();
        return $rowData;
    }

    protected function saveData($attributes = array()) {
        return DbUtil::update(Yii::app()->db, 'garden', $attributes, array('gardenId' => $this->gardenId));
    }

    public static function multiLoad($params = array(), $isSimple = true) {
        $sql = "SELECT * FROM garden";
        if (!empty($params)) {
            $sql .= ' WHERE ' . implode(' and ', $params);
        }
        $sql .= ' ORDER BY gardenSign';
        //Yii::trace($sql);
        return self::multiLoadBySql($sql, 'gardenId', array(), $isSimple);
    }

    public static function create($createInfo) {
        $insertArr = array();
        foreach (self::attributeColumns() as $key) {
            if (isset($createInfo[$key])) {
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['createTime'] = time();
        return DbUtil::insert(Yii::app()->db, 'garden', $insertArr, true);
    }

    /*
     * 获取花园表指定花园中种子的列表
     */

    public function getSeedList() {
        return $this->seedList;
    }

    public static function getSeedListBySign($playerId, $gardenSign) {
        $command = Yii::app()->db->createCommand("SELECT gardenId,seedList,fosterList,seedCount FROM garden WHERE playerId = :playerId AND gardenSign = :gardenSign");
        $command->bindParam(':playerId', $playerId);
        $command->bindParam(':gardenSign', $gardenSign);
        return $command->queryRow();
    }

    public static function getGardenId($playerId, $gardenSign) {
        $command = Yii::app()->db->createCommand("SELECT gardenId FROM garden WHERE playerId = :playerId AND gardenSign = :gardenSign");
        $command->bindParam(':playerId', $playerId);
        $command->bindParam(':gardenSign', $gardenSign);
        $queryRow = $command->queryRow();
        return $queryRow['gardenId'];
    }

    public static function getAllGardenId($playerId) {
        $command = Yii::app()->db->createCommand('SELECT gardenId FROM garden WHERE playerId=:playerId');
        $rowData = $command->bindParam(':playerId', $playerId)->queryAll();
        return $rowData;
    }

    public static function maxSeedGardenCount($playerId) {
        $command = Yii::app()->db->createCommand("SELECT COUNT(*) FROM garden WHERE playerId = :playerId AND seedCount = 9");
        $command->bindParam(':playerId', $playerId);
        return $command->queryScalar();
    }

    public static function getBackgroundByPlayerId($playerId) {
        $command = Yii::app()->db->createCommand('SELECT backGround FROM garden WHERE playerId=:playerId');
        $rowData = $command->bindParam(':playerId', $playerId)->queryAll();
        return $rowData;
    }

    /*
     * 更新花园表中最喜爱的花园的方法
     */

    public function setFavouriteGardenId($favouriteFlag) {
        $this->favouriteFlag = $favouriteFlag;
        $this->saveAttributes(array('favouriteFlag'));
    }

    /*
     * 更换花园的背景
     */

    public function setGardenBackGround($background) {
        $this->backGround = $background;
        $this->saveAttributes(array('backGround'));
    }

    public function updateDecoExtraGrow($decoration, $action) {
        $id = $decoration->id;
        $seedModel = Yii::app()->objectLoader->load('SeedModel', $this->playerId);
        $gardenId = $this->gardenId;
        $extraGrow = $this->getDecoExtraGrow($id);
        if ($action == 'add') {
            $this->decoExtraGrow += $extraGrow;
        } else if ($action == 'remove') {
            $this->decoExtraGrow -= $extraGrow;
        }
        $this->saveAttributes(array('decoExtraGrow'));
        $seedModel->updateSeedGrowValue($gardenId);
    }

    public function getDecoExtraGrow($id) {
        $arrayExtraGrow = Util::loadconfig("DecoItem");
        $extraGrow = 0;
        if (isset($arrayExtraGrow[$id])) {
            $extraGrow = $arrayExtraGrow[$id]['grow'];
        }
        return $extraGrow;
    }

    public function getDecorationInfo() {
        //获取decorationInfo的信息
        $decorationInfo = unserialize($this->decorationInfo);
        if (empty($decorationInfo)) {
            $decorationInfo = array();
        }
        return $decorationInfo;
    }

    public function setDecorationInfo($decorationInfo) {
        //设置decorationInfo的信息
        $this->decorationInfo = serialize($decorationInfo);
    }

    /*
     * 更新DecorationInfo的信息
     */

    public function addDecorationInfo($decoration, $x, $y, $dir = 0, $decorationInfo = 0) {

//        if ($x + $y <= 35 && $x + $y >= 9 && $x - $y <= 9 && $x - $y >= -9) {
        //获得decorationInfo
        if ($decorationInfo == 0)
            $decorationInfo = $this->getDecorationInfo();
        if ($dir == 0) {
            $realX = $decoration->sizeX;
            $realY = $decoration->sizeY;
        } else if ($dir == 1) {
            $realX = $decoration->sizeY;
            $realY = $decoration->sizeX;
        } else {
            throw new SException(Yii::t('Decoration', 'Direction error.'));
        }
        //调用canAddBuilding方法验证是否可以摆放装饰
        list($checkResult, $placeArray) = self::canAddBuilding($decorationInfo, $x, $y, $realX, $realY, $decoration);
        //如果不能摆放，抛出不能摆放的信息
        if ($checkResult === false) {
            throw new SException(Yii::t('Decoration', 'this place has a decoration'));
        }
        //通过两层循环来遍历$placeArray
        foreach ($placeArray as $placeX => $placeList) {
            foreach ($placeList as $placeY => $flag) {
                //判断是不是其始位置
                if (($placeX == $x) and ($placeY == $y)) {//起始位置，放置decoration信息
                    //如果是起始位置，在起始位置放置building的信息
                    if ($decoration->special == 1 || $decoration->special == 2) {
                        $decorationInfo[$placeX][$placeY] = array('id' => $decoration->id, 'direction' => $dir, 'special' => $decoration->special, 'category' => $decoration->category, 'type' => $decoration->type);
                    } else {
                        $decorationInfo[$placeX][$placeY] = array('id' => $decoration->id, 'direction' => $dir, 'category' => $decoration->category, 'type' => $decoration->type);
                    }
                } else {//其他位置，用1填充
                    //1表示有东西摆放在这个坐标点上
                    $decorationInfo[$placeX][$placeY] = DECO_INFO_CONSTRUCTION_TYPE_FULL;
                }
            }
        }
//        } else {
//            throw new CException(Yii::t('Decoration', 'you can not add here'));
//        }

        return $decorationInfo;
    }

    public function getCupInfo() {
        $decorationInfo = $this->getDecorationInfo();
        $result = array();

        foreach ($decorationInfo as $placeList) {
            foreach ($placeList as $arr) {
                if (is_array($arr)) {
                    $decoItem = Yii::app()->objectLoader->load('DecoItem', $arr['id']);
                    //单个花园如果是奖杯信息的就读到数组中
                    if ($decoItem->category == 5) {
                        $result['\'' . $arr['id'] . '\''] = $this->gardenId;
                    }
                }
            }
        }

        return $result;
    }

    public function updateCupInfo($oldCupId, $newCupId) {
        $decorationInfo = $this->getDecorationInfo();

        foreach ($decorationInfo as $X => $placeList) {
            foreach ($placeList as $Y => $arr) {
                //找到存在的信息替换掉
                if (is_array($arr)) {
                    if (isset($arr['id']) && $arr['id'] == $oldCupId) {
                        $decorationInfo[$X][$Y]['id'] = $newCupId;
                    }
                }
            }
        }

        $this->setDecorationInfo($decorationInfo);
        //更新$decorationInfo的信息
        $this->saveAttributes(array('decorationInfo'));
    }

    public function addNewSpecial($decoration) {
        $decorationInfo = $this->getDecorationInfo();

        foreach ($decorationInfo as $X => $placeList) {
            foreach ($placeList as $Y => $arr) {
                if (is_array($arr)) {
                    if (isset($arr['special']) && $arr['special'] == $decoration->special) {
                        $decorationInfo[$X][$Y]['id'] = $decoration->id;
                    }
                }
            }
        }

        $this->setDecorationInfo($decorationInfo);
        //更新$decorationInfo的信息
        $this->saveAttributes(array('decorationInfo'));
    }

    public function addValidate() {
        $result = array();
        $result['littleCount'] = 0;
        $result['middelCount'] = 0;
        $result['bigCount'] = 0;
        $decorationInfo = $this->getDecorationInfo();
        foreach ($decorationInfo as $placeList) {
            foreach ($placeList as $arr) {
                if (is_array($arr)) {
                    $decoItem = Yii::app()->objectLoader->load('DecoItem', $arr['id']);
                    if ($decoItem->special == 0) {
                        if ($decoItem->sizeType == 1) {
                            $result['littleCount']++;
                        } elseif ($decoItem->sizeType == 2) {
                            $result['middelCount']++;
                        } elseif ($decoItem->sizeType == 3) {
                            $result['bigCount']++;
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function addDecoration($decoration, $x, $y, $dir) {
        //获得decorationInfo
        $decorationInfo = $this->addDecorationInfo($decoration, $x, $y, $dir);
        //设置$decorationInfo的信息
        $this->setDecorationInfo($decorationInfo);
        //更新$decorationInfo的信息
        $this->saveAttributes(array('decorationInfo'));
    }

    public function moveDecoration($decoration, $fromx, $fromy, $tox, $toy, $dir) {
        $decorationInfo = $this->removeDecorationInfo($fromx, $fromy);
        $decorationInfo = $this->addDecorationInfo($decoration, $tox, $toy, $dir, $decorationInfo);
        //设置$decorationInfo的信息
        $this->setDecorationInfo($decorationInfo);
        //更新$decorationInfo的信息
        $this->saveAttributes(array('decorationInfo'));
    }

    //将起点坐标为$x, $y的装饰移除掉
    public function removeDecorationInfo($x, $y) {
        //获取decorationInfo的信息
        $decorationInfo = $this->getDecorationInfo();
        //判断该坐标上是否有装饰
        if (isset($decorationInfo[$x][$y]) and (is_array($decorationInfo[$x][$y]))) {
            $classKey = $decorationInfo[$x][$y]['id'];
            $decoration = Yii::app()->objectLoader->load('DecoItem', $classKey);
            $dir = $decorationInfo[$x][$y]['direction'];
            if ($dir == 0) {
                $realX = $decoration->sizeX;
                $realY = $decoration->sizeY;
            } else if ($dir == 1) {
                $realX = $decoration->sizeY;
                $realY = $decoration->sizeX;
            } else {
                throw new SException('Direction error.');
            }
            //将建筑物移除，当然是再通过两层循环来遍历标识坐标点的占地置为0
            $placeX = $x;
            while ($placeX <= ($x + $realX - 1)) {
                $placeY = $y;
                while ($placeY <= ($y + $realY - 1)) {
                    //将$decorationInfo中的标识设置为0
                    $decorationInfo[$placeX][$placeY] = DECO_INFO_CONSTRUCTION_TYPE_BLANK;  //所有占地置为0
                    $placeY++;
                }
                $placeX++;
            }

            return $decorationInfo;
        } else {
            throw new SException(Yii::t('Decoration', 'no decoration on this place'));
        }
    }

    //将起点坐标为$x, $y的装饰移除掉
    public function removeDecoration($x, $y) {
        $decorationInfo = $this->removeDecorationInfo($x, $y);
        //设置$decorationInfo的信息
        $this->setDecorationInfo($decorationInfo);
        //更新$decorationInfo的信息
        $this->saveAttributes(array('decorationInfo'));
    }

    public function getDecorationID($x, $y) {
        //获取decorationInfo的信息
        $decorationInfo = $this->getDecorationInfo();
        if (isset($decorationInfo[$x][$y]) and (is_array($decorationInfo[$x][$y]))) {
            return $decorationInfo[$x][$y]['id'];
        } else {
            throw new SException(Yii::t('Decoration', 'no decoration on this place'));
        }
    }

    //判断是否可以摆放东西。起始位置：$startX, $startY；占地：$sizeX, $sizeY
    public static function canAddBuilding($decorationInfo, $startX, $startY, $sizeX, $sizeY, $decoration) {
        //定义一个数组结构，具有返回bool值的结构
        list($checkResult, $placeArray) = array(true, array());
        $x = $startX;
        //从开始x坐标，加上占地长度，减去起始点也占一个格子
        while ($x <= ($startX + $sizeX - 1)) {
            $y = $startY;
            //从开始y坐标，加上占地长度，减去起始点也占一个格子
            while ($y <= ($startY + $sizeY - 1)) {
                //判断这个格子是否定义，是否下面存有数组，是否大于0
                if (isset($decorationInfo[$x][$y]) and (is_array($decorationInfo[$x][$y]) or ($decorationInfo[$x][$y] > 0))) {
                    //如果有数据表示一个结构为false，说明不能在这个位置摆放物品
                    $checkResult = false;
                    //将查找结构是有物品，标识放入$placeArray[$x][$y]位置
                    $placeArray[$x][$y] = DECO_INFO_CONSTRUCTION_TYPE_FULL;
                } else {
                    //将查找结构是有没有物品，标识放入$placeArray[$x][$y]位置
                    $placeArray[$x][$y] = DECO_INFO_CONSTRUCTION_TYPE_BLANK;
                }
                //自增
                $y++;
            }
            //自增
            $x++;
        }
        //返回是否能摆放物品的标识，和那些格子被占用的数组
        return array($checkResult, $placeArray);
    }

    public function getGardenDeco() {
        $i = 0;
        $result = array();
        $decorationInfo = $this->getDecorationInfo();
        foreach ($decorationInfo as $X => $placeList) {
            foreach ($placeList as $Y => $arr) {
                if (is_array($arr)) {
                    if (isset($arr['special'])) {
                        $special = $this->GardenDecoAttributes->getSpecialId($this->backGround,$arr['special']);
                        $decoItem = Yii::app()->objectLoader->load('DecoItem', $special['decoId']);
                        $result[$i]['special'] = $arr['special'];
                    } else {
                        $decoItem = Yii::app()->objectLoader->load('DecoItem', $arr['id']);
                    }
                    $result[$i]['name'] = str_replace(substr($decoItem->image, -4), '', $decoItem->image);
                    $result[$i]['x'] = $X;
                    $result[$i]['y'] = $Y;
                    $result[$i]['direction'] = $arr['direction'];
                    $result[$i]['price'] = $decoItem->sellPrice;
                    $i++;
                }
            }
        }

        return $result;
    }

    public function getGardenDecoShow() {
        $arrayList = array();
        $decoItem = Yii::app()->objectLoader->load('DecoItem', $this->backGround);
        $arrayList['background'] = str_replace(substr($decoItem->image, -4), '', $decoItem->image);
        $arrayList['ownerId'] = $this->playerId;
        $arrayList['gardenId'] = $this->gardenId;
        $arrayList['decorations'] = $this->getGardenDeco();

        $seedModel = Yii::app()->objectLoader->load('SeedModel', $this->playerId);
        $seeds = $seedModel->getGardenSeeds($this->gardenId, true);

        $result = array();
        foreach ($seeds as $seed) {
            if (!$seed->isExists()) {
                $array = array(
                    'seedId' => $seed->seedId,
                    'isExists' => 0,
                );
            } else {
                $array = $seed->getNativeData();
                $array['isExists'] = 1;
            }
            $result[$seed->seedId] = $array;
        }

        $arrayList['seeds'] = $result;

        return $arrayList;
    }

    //thisPlayerId当前登录的玩家
    //$playerId访问的
    public static function gardenType($thisPlayerId, $playerId) {
        $isfriend = PlayerFriend::isFriend($thisPlayerId, $playerId);
        if ($isfriend == 5) {
            //自己的
            return 1;
        } else if ($isfriend == 0) {
            //好友的
            return 2;
        } else if ($isfriend == 1 || $isfriend == 2 || $isfriend == 4) {
            //申请的：包括申请，待确认，非好友
            return 3;
        } else {
            //其它的
            return 4;
        }
    }

    public function initGardenDeco() {
        $special = $this->GardenDecoAttributes->getSpecial(GARDEN_DEFAULTBACKGROUND);
        $decoration1 = Yii::app()->objectLoader->load('DecoItem', $special['decoId1']);
        $decoration1->special = 1;
        $this->addDecorationGarden($this->gardenId, $decoration1, 10, 16, 0);
        $decoration2 = Yii::app()->objectLoader->load('DecoItem', $special['decoId2']);
        $decoration2->special = 2;
        $this->addDecorationGarden($this->gardenId, $decoration2, 14, 12, 0);
    }

    public function getFosterState( $playerId ) {
        $fosterSeedNum = Seed::FosterSeedNum($playerId);
        $player = Yii::app()->objectLoader->load('Player',$playerId);
        if( !empty($this->fosterList) ) {
            $seed = Yii::app()->objectLoader->load('Seed',intval($this->fosterList));
            $state = $seed->playerId!=$playerId?2:1 ;
        }
        else {
            if ($fosterSeedNum >= min(max(1,intval($player->level/10)),9) ){
                return  2;
            }
            if( PlayerFriend::isFriend($this->playerId,$playerId)==0 ) {
                $friend = Yii::app()->objectLoader->load('PlayerFriend',PlayerFriend::makeKey($playerId,$this->playerId));
                $state = empty( $friend->fosterSeed )?0:2 ;
            }
            else {
                $state = 0 ;
            }
        }
        return $state ;
    }

}
