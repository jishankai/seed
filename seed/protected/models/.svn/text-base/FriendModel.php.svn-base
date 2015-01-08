<?php

class FriendModel extends Model {
    private $player;
    private $playerId;

    public function __construct($playerId) {
        $this->playerId = $playerId;
    }

    /*获取玩家信息*/
    public static function getplayerInfo($playerId) {
        $data = array();
        $player = Yii::app()->objectLoader->load('Player', $playerId);//玩家类型
        foreach ($player->attributeNames() as $key) {
            $data[$key] = $player->$key;
        }
        return $data;
    }

    /*获取一系列玩家信息*/
    public static function getPlayerList($Id) {
        $idList = explode("-",$Id);
        $playerList = array();
        $num = count($idList);
        for ($i=0; $i<$num; $i++) {
            $playerinfo = self::getplayerInfo($idList[$i]);
            $player = Yii::app()->objectLoader->load('Player', $idList[$i]);
            $favouriteSeed = $player->getFavouriteSeed();
            if ( !empty($favouriteSeed)) {
                $seed = Yii::app()->objectLoader->load('Seed', $favouriteSeed);
                $seedData = $seed->getDisplayData();
            }else {
                $seedData = null;
            }
            array_push ($playerList, array('Id' => $idList[$i], 'player' => $playerinfo, 'seed' => $seedData));
        }
        return array('playerList' => $playerList, 'num' => $num);
    }

    /*获取玩家好友列表*/

    public static function getFriendInfo($playerId,$numPerPage,$page = 1,$num) {
        $friendList = PlayerFriend::getFriendId($playerId, FRIEND_STATUS_ACCEPT, $numPerPage, $page, $num);
        $playerFriendList = array();
        foreach($friendList as $playerFriend) {
            $friend = Yii::app()->objectLoader->load('Player', $playerFriend->friendId);
            $fosterSeedId = $playerFriend->fosterSeed;
            if (!empty($fosterSeedId)) {
                $fosterSeed = Yii::app()->objectLoader->load('Seed', $fosterSeedId);
            }else {
                $fosterSeed = null;
            }
            $favouriteSeed = $friend->getFavouriteSeed();
            if (!empty($favouriteSeed)) {
                $seed = Yii::app()->objectLoader->load('Seed', $favouriteSeed);
            }else {
                $seed = null;
            }
            if (!empty($fosterSeedId)) {
                $playerFriendList['foster'][] = array('playerFriend' => $playerFriend, 'friend' => $friend, 'seed' => $seed, 'fosterSeedId' => $fosterSeedId, 'fosterSeed'=>$fosterSeed);
            } else {
                $playerFriendList['nofoster'][] = array('playerFriend' => $playerFriend, 'friend' => $friend, 'seed' => $seed, 'fosterSeedId' => $fosterSeedId, 'fosterSeed'=>$fosterSeed);
            }
        }

        return $playerFriendList;
    }

    /*通过Name获取好友*/
    public static function getFriendInfoByName($playerId, $searchId) {
        $friendId = PlayerFriend::getFriendIdByName($searchId);
        $friendNum = 1;
        $friendList = array();
        if (PlayerFriend::isFriend($playerId,$friendId) == 0) {	
            $playerFriend = Yii::app()->objectLoader->load('PlayerFriend', PlayerFriend::makeKey($playerId, $friendId));
            array_push($friendList, $playerFriend);
        }
        $playerFriendList = array();
        foreach($friendList as $playerFriend) {
            $friend = Yii::app()->objectLoader->load('Player', $playerFriend->friendId);
            $fosterSeedId = $playerFriend->fosterSeed;
            if (!empty($fosterSeedId)) {
                $fosterSeed = Yii::app()->objectLoader->load('Seed', $fosterSeedId);
            }else {
                $fosterSeed = null;
            }
            $favouriteSeed = $friend->getFavouriteSeed();
            if (!empty($favouriteSeed)) {
                $seed = Yii::app()->objectLoader->load('Seed', $favouriteSeed);
            }else {
                $seed = null;
            }
            if (!empty($fosterSeedId)) {
                $playerFriendList['foster'][] = array('playerFriend' => $playerFriend, 'friend' => $friend, 'seed' => $seed, 'fosterSeedId' => $fosterSeedId, 'fosterSeed'=>$fosterSeed);
            } else {
                $playerFriendList['nofoster'][] = array('playerFriend' => $playerFriend, 'friend' => $friend, 'seed' => $seed, 'fosterSeedId' => $fosterSeedId, 'fosterSeed'=>$fosterSeed);
            }
        }

        return $playerFriendList;
    }

    /*获取随即访问列表*/

    public static function getRandomFriend($playerId, $ranNum, $ranNum1) {
        $playerLv = Yii::app()->objectLoader->load('Player', $playerId)->level;
        $playerIdList1 = PlayerFriend::getRandomFriendId1($playerLv);
        $playerIdList2 = PlayerFriend::getRandomFriendId2($playerLv);
        $friendIdList1 = array();
        $friendIdList2 = array();
        foreach($playerIdList1 as $Id) {
            $status = PlayerFriend::isFriend($playerId,$Id);
            $isadd = FriendInfo::addFriendFlag($Id);
            if ($status == 4 or $status == 2 or $status == 3) {
            	if ($isadd == 1) {
                	array_push($friendIdList1, array('Id' => $Id, 'status' => $status));
            	}
            }	
        }
        foreach($playerIdList2 as $Id) {
            $status = PlayerFriend::isFriend($playerId,$Id);
            $isadd = FriendInfo::addFriendFlag($Id);
            if ($status == 4 or $status == 2 or $status == 3) {
            	if ($isadd == 1) {
                	array_push($friendIdList2, array('Id' => $Id, 'status' => $status));
            	}
            }	
        }
        $num1 = count($friendIdList1);
        $num2 = count($friendIdList2);
        if ($num1 < $ranNum1) {
            $ranNum1 = $num1;
        }
        $ranNum2 = $ranNum-$ranNum1;
        if ($num2 < $ranNum2) {
            $ranNum2 = $num2;
        }
        if ((($ranNum-$ranNum2) > $ranNum1) && ($ranNum1 < $num1)) {
            $ranNum1 += min($ranNum-$ranNum2-$ranNum1,$num1-$ranNum1);
        }
        if ($ranNum1 == 0 && $ranNum2 == 0) {
            throw new SException(Yii::t('Friend', 'system error'));
        }
        $friendList = array();
        $idList = array();
        if ($ranNum1 != 0) {
            $randomIdList1 = (array)array_rand($friendIdList1, $ranNum1);
            foreach($randomIdList1 as $Id) {
                $player = Yii::app()->objectLoader->load('Player', $friendIdList1[$Id]['Id']);
                $favouriteSeed = $player->getFavouriteSeed();
                if (!empty($favouriteSeed)) {
                    $seed = Yii::app()->objectLoader->load('Seed', $favouriteSeed);
                }else {
                    $seed = null;
                }
                array_push($friendList, array('playerId' => $friendIdList1[$Id]['Id'], 'player' => $player, 'status' => $friendIdList1[$Id]['status'], 'seed' => $seed));
                $idList[] = array('id'=>$friendIdList1[$Id]['Id'], 'pC'=>null);
            }
        }
        if ($ranNum2 != 0) {
            $randomIdList2 = (array)array_rand($friendIdList2, $ranNum2);
            foreach($randomIdList2 as $Id) {
                $player = Yii::app()->objectLoader->load('Player', $friendIdList2[$Id]['Id']);
                $favouriteSeed = $player->getFavouriteSeed();
                if (!empty($favouriteSeed)) {
                    $seed = Yii::app()->objectLoader->load('Seed', $favouriteSeed);
                }else {
                    $seed = null;
                }
                array_push($friendList, array('playerId' => $friendIdList2[$Id]['Id'], 'player' => $player, 'status' => $friendIdList2[$Id]['status'], 'seed' => $seed));
                $idList[] = array('id'=>$friendIdList2[$Id]['Id'], 'pC'=>null);
            }			
        }		
        FriendInfo::setCDTime($playerId);
        $cdTime = FriendInfo::getCDTime($playerId);
        return array('friendList' => $friendList, 'friendIdList' => $idList, 'cdTime' => $cdTime);
    }

    public static function getRandomFriendById($playerId) {
        $randomList = FriendInfo::getRandomList($playerId);
        $friendList = array();
        if (!empty($randomList)) {
            $RandomList = unserialize($randomList);
            foreach ($RandomList as $Random) {
                $Id = $Random['id'];
                $player = Yii::app()->objectLoader->load('Player', $Id);
                $favouriteSeed = $player->getFavouriteSeed();
                if (!empty($favouriteSeed)) {
                    $seed = Yii::app()->objectLoader->load('Seed', $favouriteSeed);
                }else {
                    $seed = null;
                }
                $status = PlayerFriend::isFriend($playerId,$Id);
                array_push($friendList, array('playerId' => $Id, 'player' => $player, 'status' => $status, 'seed' => $seed));
            }
        }
        $cdTime = FriendInfo::getCDTime($playerId);
        return array('friendList' => $friendList, 'cdTime' => $cdTime);
    }

    /*根据招待ID搜索用户*/

    public static function searchPlayerByInviteId($playerId, $inviteId) {
        $playerIdList = PlayerFriend::getPlayerByInviteId($inviteId);
        $playerList = array();
        foreach ($playerIdList as $Id) {
            $status = PlayerFriend::isFriend($playerId, $Id);
            if ($status != 5) {
                $player = Yii::app()->objectLoader->load('Player', $Id);
                $favouriteSeed = $player->getFavouriteSeed();
                if (!empty($favouriteSeed)) {
                    $seed = Yii::app()->objectLoader->load('Seed', $favouriteSeed);
                }else {
                    $seed = null;
                }
                array_push($playerList, array('playerId' => $Id, 'player' => $player, 'status' => $status, 'seed' =>$seed));
            }
        }
        return array('playerList' => $playerList);
    }

    /*获取等待批准的好友列表*/

    public static function getFriendRequestList($playerId,$numPerPage,$page = 1,$num) {
        $friendList = PlayerFriend::getFriendId($playerId, FRIEND_STATUS_CONFIRMING, $numPerPage, $page,$num);
        $playerFriendList = array();
        foreach($friendList as $playerFriend) {
            $friend = Yii::app()->objectLoader->load('Player', $playerFriend->friendId);
            $favouriteSeed = $friend->getFavouriteSeed();
            if (!empty($favouriteSeed)) {
                $seed = Yii::app()->objectLoader->load('Seed', $favouriteSeed);
            }else {
                $seed = null;
            }
            array_push($playerFriendList, array('playerFriend' => $playerFriend, 'friend' => $friend, 'seed' => $seed));
        }
        return $playerFriendList;
    } 

    /*获取等待批注的好友个数*/

    public static function getFriendRequestNum($playerId) {
        $status = FRIEND_STATUS_CONFIRMING;
        return PlayerFriend::getFriendNum($playerId, $status);
    }

    /*发送好友请求*/

    public static function createFriendRequest($playerId, $friendId) {
    	if ($playerId == $friendId) {
            throw new SException(Yii::t('Friend', 'system error'));
        }
        $playerFriend1 = Yii::app()->objectLoader->load('PlayerFriend', PlayerFriend::makeKey($playerId, $friendId));
        $playerFriend2 = Yii::app()->objectLoader->load('PlayerFriend', PlayerFriend::makeKey($friendId, $playerId));
        if ($playerFriend1->isExists()) {
            $status1 = $playerFriend1->status;
        }
        if ($playerFriend2->isExists()) {
            $status2 = $playerFriend2->status;
        }
        if (!isset($status1) and !isset($status2)) {//没有过好友请求——添加一个好友请求
                $playerFriendNum = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
                if ($playerFriendNum >= MAX_FRIEND_NUM) {//player的好友数已经达到了上限，不能再发送好友申请了，此时跳提示弹窗，不发送好友申请，页面无变化
                    throw new SException(Yii::t('Friend', 'player friend reach max'));
                }
                $friendFriendNum = PlayerFriend::getFriendNum($friendId, FRIEND_STATUS_ACCEPT);
                if ($friendFriendNum >= MAX_FRIEND_NUM) {//friend的好友数已经达到了上限，不能再加为好友了，此时跳提示弹窗，页面无变化
                    throw new SException(Yii::t('Friend', 'friend friend reach max'));
                }
                PlayerFriend::createFriendRequest($playerId, $friendId);
                $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$friendId);
                $globalMessage->addMessage($playerId,MESSAGE_TYPE_FRIEND_INVITE);

                //任务检查
                $missionEvent = new MissionEvent($playerId, MISSIONEVENT_FRIEND);
                $missionEvent->onMissionComplete();
                //任务检查
                $missionEvent = new MissionEvent($playerId, MISSIONEVENT_FRIENDVISIT, array('behavior'=>'friend', 'friendId'=>$friendId));
                $missionEvent->onMissionComplete();
        }
        else if (($status1 == FRIEND_STATUS_CONFIRMING) and ($status2 == FRIEND_STATUS_APPLYING)) {//对方已经发送过好友请求——批准这个好友请求
        		$playerFriendNum = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
                if ($playerFriendNum >= MAX_FRIEND_NUM) {//player的好友数已经达到了上限，不能再接受好友申请了，此时跳提示弹窗，页面无变化
                    throw new SException(Yii::t('Friend', 'player friend reach max', array('{maxFriendNum}' => MAX_FRIEND_NUM)));
                }
                $friendFriendNum = PlayerFriend::getFriendNum($friendId, FRIEND_STATUS_ACCEPT);
                if ($friendFriendNum >= MAX_FRIEND_NUM) {//friend的好友数已经达到了上限，不能再加为好友了，此时跳提示弹窗，页面无变化
                    throw new SException(Yii::t('Friend', 'friend friend reach max'));
                }
                $playerFriend1->status = FRIEND_STATUS_ACCEPT;
                $playerFriend2->status = FRIEND_STATUS_ACCEPT;
                $playerFriend1->saveAttributes(array('status'));
                $playerFriend2->saveAttributes(array('status'));
                PlayerFriend::statusChange($playerId, $friendId);
                PlayerFriend::statusChange($friendId, $playerId);

                //成就检查
                $achieveEvent = new AchievementEvent($playerId, ACHIEVEEVENT_FRIEND);
                $achieveEvent->onAchieveComplete();
                $achieveEvent = new AchievementEvent($friendId, ACHIEVEEVENT_FRIEND);
                $achieveEvent->onAchieveComplete();

                //任务检查
                $missionEvent = new MissionEvent($playerId, MISSIONEVENT_FRIEND);
                $missionEvent->onMissionComplete();
                //任务检查
                $missionEvent = new MissionEvent($playerId, MISSIONEVENT_FRIENDVISIT, array('behavior'=>'friend', 'friendId'=>$friendId));
                $missionEvent->onMissionComplete();
        }
        else if (($status1 == FRIEND_STATUS_DELETE) and ($status2 == FRIEND_STATUS_DELETE)) {//刚删除的好友
        		$playerFriendNum = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
                if ($playerFriendNum >= MAX_FRIEND_NUM) {//player的好友数已经达到了上限，不能再发送好友申请了，此时跳提示弹窗，不发送好友申请，页面无变化
                    throw new SException(Yii::t('Friend', 'player friend reach max', array('{maxFriendNum}' => MAX_FRIEND_NUM)));
                }
                $friendFriendNum = PlayerFriend::getFriendNum($friendId, FRIEND_STATUS_ACCEPT);
                if ($friendFriendNum >= MAX_FRIEND_NUM) {//friend的好友数已经达到了上限，不能再加为好友了，此时跳提示弹窗，页面无变化
                    throw new SException(Yii::t('Friend', 'friend friend reach max'));
                }
                $playerFriend1->status = FRIEND_STATUS_APPLYING;
                $playerFriend2->status = FRIEND_STATUS_CONFIRMING;
                $playerFriend1->saveAttributes(array('status'));
                $playerFriend2->saveAttributes(array('status'));
                PlayerFriend::statusChange($playerId, $friendId);
                PlayerFriend::statusChange($friendId, $playerId);
                $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$friendId);
                $globalMessage->addMessage($playerId,MESSAGE_TYPE_FRIEND_INVITE);

                //任务检查
                $missionEvent = new MissionEvent($playerId, MISSIONEVENT_FRIEND);
                $missionEvent->onMissionComplete();
                //任务检查
                $missionEvent = new MissionEvent($playerId, MISSIONEVENT_FRIENDVISIT, array('behavior'=>'friend', 'friendId'=>$friendId));
                $missionEvent->onMissionComplete();
        }
        else if (($status1 == FRIEND_STATUS_APPLYING) and ($status2 == FRIEND_STATUS_CONFIRMING)) {//自己已经发送过好友请求
                throw new SException(Yii::t('Friend', 'system error'));
        }
        else if (($status1 == FRIEND_STATUS_ACCEPT) and ($status2 == FRIEND_STATUS_ACCEPT)) {//已经是好友了
                throw new SException(Yii::t('Friend', 'system error'));
        }
        else {
                throw new SException(Yii::t('Friend', 'system error'));
        }
    }

    /*批准一个好友请求，player进行操作，同意friend发送的请求*/
    public static function confirmFriendRequest($playerId, $friendId) {
        if ($playerId == $friendId) {
            throw new SException(Yii::t('Friend', 'accept friend request error'));
        }
        $playerFriend1 = Yii::app()->objectLoader->load('PlayerFriend', PlayerFriend::makeKey($playerId, $friendId));
        $playerFriend2 = Yii::app()->objectLoader->load('PlayerFriend', PlayerFriend::makeKey($friendId, $playerId));
        $status1 = $playerFriend1->status;
        $status2 = $playerFriend2->status;

        $transaction = Yii::app()->db->beginTransaction();
        try {
            if (($playerFriend1->status == FRIEND_STATUS_CONFIRMING) and ($playerFriend2->status == FRIEND_STATUS_APPLYING)) {//对方已经发送过好友请求——批准这个好友请求
                $playerFriendNum = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
                if ($playerFriendNum >= MAX_FRIEND_NUM) {//player的好友数已经达到了上限，不能再接受好友申请了，此时跳提示弹窗，页面无变化
                    throw new SException(Yii::t('Friend', 'player friend reach max2'));
                }
                $friendFriendNum = PlayerFriend::getFriendNum($friendId, FRIEND_STATUS_ACCEPT);
                if ($friendFriendNum >= MAX_FRIEND_NUM) {//friend的好友数已经达到了上限，不能再加为好友了，此时跳提示弹窗，页面无变化
                    throw new SException(Yii::t('Friend', 'friend friend reach max'));
                }
                $playerFriend1->status = FRIEND_STATUS_ACCEPT;
                $playerFriend2->status = FRIEND_STATUS_ACCEPT;
                $playerFriend1->saveAttributes(array('status'));
                $playerFriend2->saveAttributes(array('status'));
                PlayerFriend::statusChange($playerId, $friendId);
                PlayerFriend::statusChange($friendId, $playerId);

                //成就检查
                $achieveEvent = new AchievementEvent($playerId, ACHIEVEEVENT_FRIEND);
                $achieveEvent->onAchieveComplete();
                $achieveEvent = new AchievementEvent($friendId, ACHIEVEEVENT_FRIEND);
                $achieveEvent->onAchieveComplete();
            }
            else if (!isset($status1) and !isset($status2)) {//对方未发送过好友请求
                throw new SException(Yii::t('Friend', 'system error'));
            }
            else if (($status1 == FRIEND_STATUS_ACCEPT) and ($status2 == FRIEND_STATUS_ACCEPT)) {//已经是好友了
                throw new SException(Yii::t('Friend', 'system error'));
            }
            else if (($status1 == FRIEND_STATUS_APPLYING) and ($status2 == FRIEND_STATUS_CONFIRMING)) {//自己发送的好友请求，对方未发送过
                throw new SException(Yii::t('Friend', 'system error'));
            }
            else {
                throw new SException(Yii::t('Friend', 'system error'));
            }
            $transaction->commit();
        }
        catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }   
    }

    /*拒绝一个好友请求，player进行操作，拒绝friend的好友请求*/
    public static function refuseFriendRequest($playerId, $friendId) {
        if ($playerId == $friendId) {
            throw new SException(Yii::t('Friend', 'refuse friend request error'));
        }
        $playerFriend1 = Yii::app()->objectLoader->load('PlayerFriend', PlayerFriend::makeKey($playerId, $friendId));
        $playerFriend2 = Yii::app()->objectLoader->load('PlayerFriend', PlayerFriend::makeKey($friendId, $playerId));

        $transaction = Yii::app()->db->beginTransaction();
        try {
            if (($playerFriend1->status == FRIEND_STATUS_CONFIRMING) and ($playerFriend2->status == FRIEND_STATUS_APPLYING)) {//对方发送过好友请求——拒绝这个请求
                $playerFriend1->deleteData();
                $playerFriend2->deleteData();
            }
            else if (!isset($status1) and !isset($status2)) {//对方未发送过好友请求
                throw new SException(Yii::t('Friend', 'system error'));
            }
            else if (($status1 == FRIEND_STATUS_ACCEPT) and ($status2 == FRIEND_STATUS_ACCEPT)) {//已经是好友了
                throw new SException(Yii::t('Friend', 'system error'));
            }
            else if (($status1 == FRIEND_STATUS_APPLYING) and ($status2 == FRIEND_STATUS_CONFIRMING)) {//自己发送的好友请求，对方未发送过
                throw new SException(Yii::t('Friend', 'system error'));
            }
            else {
                throw new SException(Yii::t('Friend', 'system error'));
            }
            $transaction->commit();
        }
        catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /*删除一个好友，player进行操作，删除friend这个好友*/
    public static function deleteFriend($playerId, $friendId) {
        if ($playerId == $friendId) {
            throw new SException(Yii::t('Friend', 'delete friend error'));
        }
        $playerFriend1 = Yii::app()->objectLoader->load('PlayerFriend', PlayerFriend::makeKey($playerId, $friendId));
        $playerFriend2 = Yii::app()->objectLoader->load('PlayerFriend', PlayerFriend::makeKey($friendId, $playerId));

        $transaction = Yii::app()->db->beginTransaction();
        try {
            if (($playerFriend1->status == FRIEND_STATUS_ACCEPT) and ($playerFriend2->status == FRIEND_STATUS_ACCEPT)) {//已经是好友了，删除好友
                $fosterSeed1 = $playerFriend1->fosterSeed;
                $fosterSeed2 = $playerFriend2->fosterSeed;
                try {
                    if ($fosterSeed1!=null) {
                        $fosterModel1 = Yii::app()->objectLoader->load('FosterModel', $playerFriend1->playerId);
                        $fosterModel1->seedToMail($fosterSeed1, $playerFriend1->friendId);
                    }
                    if ($fosterSeed2!=null) {
                        $fosterModel2 = Yii::app()->objectLoader->load('FosterModel', $playerFriend2->playerId);
                        $fosterModel2->seedToMail($fosterSeed2, $playerFriend2->friendId);
                    }
                } catch (Exception $e) {
                    throw $e;
                }
                $playerFriend1->status = FRIEND_STATUS_DELETE;
                $playerFriend2->status = FRIEND_STATUS_DELETE;
                $playerFriend1->saveAttributes(array('status'));
                $playerFriend2->saveAttributes(array('status'));
                PlayerFriend::statusChange($playerId, $friendId);
                PlayerFriend::statusChange($friendId, $playerId);
            }
            else if (($playerFriend1->status == FRIEND_STATUS_CONFIRMING) and ($playerFriend2->status == FRIEND_STATUS_APPLYING)) {//对方发送了好友请求，尚未批准
                throw new SException(Yii::t('Friend', 'system error'));
            }
            else if (!isset($status1) and !isset($status2)) {//对方未发送过好友请求
                throw new SException(Yii::t('Friend', 'system error'));
            }
            else if (($status1 == FRIEND_STATUS_APPLYING) and ($status2 == FRIEND_STATUS_CONFIRMING)) {//自己发送的好友请求，尚未被批准
                throw new SException(Yii::t('Friend', 'system error'));
            }
            else {
                throw new SException(Yii::t('Friend', 'system error'));
            }
            $transaction->commit();
        }
        catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public static function getRecycleInfo($playerId) {
        $detailList = PlayerFriend::getFosterFriend($playerId);
        $seedIds = array();
        if (!empty($detailList)) {
            foreach ($detailList as $row) {
                $fosterSeed = Yii::app()->objectLoader->load('Seed', $row['seedId']);
                $fosterModel = Yii::app()->objectLoader->load('FosterModel', $playerId);
                $fosterModel->seedToMail($row['seedId'], $row['friendId']);
                array_push($seedIds, $row['seedId']);
            }
        }
        return $seedIds;
    }
}
