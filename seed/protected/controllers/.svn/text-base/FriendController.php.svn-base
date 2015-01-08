<?php
class FriendController extends Controller {
    /*好友列表*/	
    public function actionIndex(){
        $this->layout = "//layouts/theme";
        $page = 1;
        $playerId = $this->playerId;
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        $guideModel = Yii::app()->objectLoader->load('GuideModel',$playerId);
        $isGuide=$guideModel->isCurrentGuide( 100 );
        $friendList = FriendModel::getFriendInfo($playerId, FRIEND_INDEX_PAGE_NUM, $page, 0);
        $totalCount = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
        $fosterNum = PlayerFriend::getFosterNum($playerId);
        $fosterMax = FosterModel::getLevelFoster($player->level);
        $foster = PlayerFriend::getFosterFriend($playerId);
        if (!empty($foster)) {
            $isfoster = 1;
        }else {
            $isfoster = 0;
        }

        $displayData = array('currentPage'=> $page,
            'player' => $player,
            'totalCount' => $totalCount,
            'fosterNum' => $fosterNum,
            'fosterMax' => $fosterMax,
            'isfoster' => $isfoster,
            'playerFriendList' => $friendList,
        	'isGuide' => $isGuide,
        	'category' => 0,            
        ); 
        //添加符合等级要求的虚拟好友
        $id = VisualFriend::checkVisualFriend($this->playerId);
        if ($id) {
            $key = VisualFriend::createKey($id, $this->playerId);
            $visualFriend = Yii::app()->objectLoader->load('VisualFriend', $key);
            $displayData['totalCount']++;
            if ($visualFriend->isFoster()) {
                $fosterNum++;
                $isfoster = 1;
                $displayData['fosterNum'] = $fosterNum;
                $displayData['isfoster'] = $isfoster;
            }
            $displayData['visualFriend'] = $visualFriend;
        }

        

        $this->display('friendList', $displayData);
    }

    /*更多好友*/
    public function actionMoreFriend() {
        if(isset($_GET['page'])) {
            $num = $_GET['delnum'];
            $page = ceil($_GET['page'])+1;
            $playerId = $this->playerId;
            $friendList = FriendModel::getFriendInfo($playerId, FRIEND_INDEX_PAGE_NUM, $page, $num);
            $Info = PlayerFriend::moreFriendInfo($friendList,$page);
            $Pmessage = $Info['Pmessage'];
            $figure = $Info['figure'];
            $this->display(array('currentPage'=>$page,'figure'=>$figure,'Pmessage'=>$Pmessage));
        }else {
            throw new SException(Yii::t('View', 'param error'));
        }
    }
    
    /*引导添加虚拟好友*/
    public function actionGetVisualFriend() {
    	$id = VisualFriend::checkVisualFriend($this->playerId);
    	if ($id) {
    		$key = VisualFriend::createKey($id, $this->playerId);
            $visualFriend = Yii::app()->objectLoader->load('VisualFriend', $key);
            $Info = PlayerFriend::visualFriendInfo($visualFriend);
            $Pmessage = $Info['Pmessage'];
            $figure = $Info['figure'];
            $this->display(array('figure'=>$figure,'Pmessage'=>$Pmessage));
    	}
    }

    public function actionMoreFriend2() {
        if(isset($_GET['page'])) {
            $page = ceil($_GET['page'])+1;
            $playerId = $this->playerId;
            $friendList = FriendModel::getFriendInfo($playerId, FRIEND_INDEX_PAGE_NUM, $page,0);
            $Info = PlayerFriend::moreFriendInfo2($friendList,$page);
            $Pmessage = $Info['Pmessage'];
            $figure = $Info['figure'];
            $this->display(array('currentPage'=>$page,'figure'=>$figure,'Pmessage'=>$Pmessage));
        }else {
            throw new SException(Yii::t('View', 'param error'));
        }
    }

    /*好友请求*/

    public function actionBeFriendList() {
        $page = 1;
        $playerId = $this->playerId;
        $player = $player = Yii::app()->objectLoader->load('Player', $playerId);
        $totalCount = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
        $requestList = FriendModel::getFriendRequestList($playerId, FRIEND_INDEX_PAGE_NUM, $page,0);
        $num = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_CONFIRMING);
        $this->layout = "//layouts/theme";
        $this->display('beFriendList', array('currentPage' => $page,
            'player' => $player,
            'totalCount' => $totalCount,
            'num' =>$num,
            'friendRequestList' => $requestList,
        ));
    }

    public function actionMoreFriend3() {
        if(isset($_GET['page'])) {
            $num = $_GET['delnum'];
            $page = ceil($_GET['page'])+1;
            $playerId = $this->playerId;
            $requestList = FriendModel::getFriendRequestList($playerId, FRIEND_INDEX_PAGE_NUM, $page,$num);
            $Info = PlayerFriend::moreFriendInfo3($requestList,$page);
            $Pmessage = $Info['Pmessage'];
            $figure = $Info['figure'];
            $this->display(array('currentPage'=>$page,'Pmessage'=>$Pmessage,'figure'=>$figure));
        }else {
            throw new SException(Yii::t('View', 'param error'));
        }
    }

    public function actionBeFriendList2() {
        $this->actionType = REQUEST_TYPE_AJAX ;
        if(isset($_GET['page']) && isset($_GET['Opage'])) {
            $page = $_GET['page'];
            $Opage = $_GET['Opage'];
            $Id = $_GET['Id'];
            $playerId = $this->playerId;
            $playerList = FriendModel::getPlayerList($Id);
            $friendList = $playerList['playerList'];
            $num = $playerList['num'];
            $this->display(array('page'=>$page, 'Opage'=>$Opage, 'friendList'=>$friendList, 'num'=>$num));
        }
        else {
            throw new SException(Yii::t('View', 'param error'));
        }
    }

    /*随即访问*/
    public function actionRandomFriend() {
        $this->layout = "//layouts/theme";
        $playerId = $this->playerId;
        $player = $player = Yii::app()->objectLoader->load('Player', $playerId);
        $totalCount = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
        $type = 1;
        $cdTime = FriendInfo::getCDTime($playerId);
        if ($cdTime != 0) {
            $playerFriendList = FriendModel::getRandomFriendById($playerId);
            $friendList = $playerFriendList['friendList'];
            $cdTime = $playerFriendList['cdTime'];
            $this->display('randomFriend', array('type'=>$type, 'player' => $player, 'totalCount' => $totalCount, 'playerFriendList' => $friendList, 'cdTime' => $cdTime , 'isGuide' => 0));
        }else {
            $playerFriendList = FriendModel::getRandomFriend($playerId, RANDOM_NUM, RANDOM_NUM2);
            $friendList = $playerFriendList['friendList'];
            $cdTime = $playerFriendList['cdTime'];
            $friendIdList = $playerFriendList['friendIdList'];
            $idList = serialize($friendIdList);
            FriendInfo::saveRandomList($playerId, $idList);
            $this->display('randomFriend', array('type'=>$type,'inviteId'=>'', 'player' => $player, 'totalCount' => $totalCount, 'playerFriendList' => $friendList, 'cdTime' => $cdTime, 'isGuide' => 0));
        }
    }

    /*搜索好友*/

    public function actionSearchFriend() {
        if(isset($_GET['inviteId'])){
            $inviteId = $_GET['inviteId'];
        }else {
            $inviteId = "";
        }
        $this->layout = "//layouts/theme";
        $playerId = $this->playerId;
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        $guideModel = Yii::app()->objectLoader->load('GuideModel',$playerId);
        $isGuide=$guideModel->isCurrentGuide( 100 );
        $totalCount = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
        $type = 2;
        if ($inviteId != "") {
            $playerList = FriendModel::searchPlayerByInviteId($playerId, $inviteId);
        }else {
            $playerFriendList = FriendModel::getRandomFriendById($playerId);
            $friendList = $playerFriendList['friendList'];
            $playerList = array('playerList' => $friendList);
            $type = 1;
        }
        $friendList = $playerList['playerList'];
        $cdTime = FriendInfo::getCDTime($playerId);
        $this->display('randomFriend', array('type'=>$type,'inviteId'=>$inviteId, 'player' => $player, 'totalCount' => $totalCount, 'playerFriendList'=> $friendList, 'cdTime' => $cdTime, 'isGuide' => $isGuide));
    }
    
    /*Ajax读取好友申请、随机好友、好友列表*/
    
    public function actionAjaxShow($category) {
    	$this->layout = "//layouts/theme";
        if (isset($_REQUEST['category'])) {
            $category = $_REQUEST['category'];
        }
        $playerId = $this->playerId;
        $player = $player = Yii::app()->objectLoader->load('Player', $playerId);
        switch ($category) {
        	case 0:
        		$page = 1;
        		$guideModel = Yii::app()->objectLoader->load('GuideModel',$playerId);
        		$isGuide=$guideModel->isCurrentGuide( 100 );
        		$friendList = FriendModel::getFriendInfo($playerId, FRIEND_INDEX_PAGE_NUM, $page, 0);
        		$totalCount = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
        		$fosterNum = PlayerFriend::getFosterNum($playerId);
        		$fosterMax = FosterModel::getLevelFoster($player->level);
        		$foster = PlayerFriend::getFosterFriend($playerId);
        		if (!empty($foster)) {
            		$isfoster = 1;
        		}else {
            		$isfoster = 0;
        		}

        		$params = array(
        			'currentPage'=> $page,
            		'player' => $player,
            		'totalCount' => $totalCount,
            		'fosterNum' => $fosterNum,
            		'fosterMax' => $fosterMax,
            		'isfoster' => $isfoster,
            		'playerFriendList' => $friendList,
        			'isGuide' => $isGuide,
        			'category' => $category,            
        		); 
        		//添加符合等级要求的虚拟好友
        		$id = VisualFriend::checkVisualFriend($this->playerId);
        		if ($id) {
            		$key = VisualFriend::createKey($id, $this->playerId);
            		$visualFriend = Yii::app()->objectLoader->load('VisualFriend', $key);
            		$params['totalCount']++;
            		if ($visualFriend->isFoster()) {
               			$fosterNum++;
                		$isfoster = 1;
                		$params['fosterNum'] = $fosterNum;
                		$params['isfoster'] = $isfoster;
            		}
            		$params['visualFriend'] = $visualFriend;
        		}

        		$viewFile = 'friendList';
        		$data = $this->renderPartial($viewFile, $params, true);
        		break;
        	case 1:
        		if(isset($_GET['inviteId'])){
            		$inviteId = $_GET['inviteId'];
        		}else {
            		$inviteId = "";
        		}
        		$guideModel = Yii::app()->objectLoader->load('GuideModel',$playerId);
        		$isGuide=$guideModel->isCurrentGuide( 100 );
		        $totalCount = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
		        $id = VisualFriend::checkVisualFriend($this->playerId);
		        if ($id) {
		        	$totalCount++;
		        }
        		$type = 2;
        		if ($inviteId != "") {
            		$playerList = FriendModel::searchPlayerByInviteId($playerId, $inviteId);
        		}else {
            		$playerFriendList = FriendModel::getRandomFriendById($playerId);
            		$friendList = $playerFriendList['friendList'];
            		$playerList = array('playerList' => $friendList);
            		$type = 1;
        		}
        		$friendList = $playerList['playerList'];
        		$cdTime = FriendInfo::getCDTime($playerId);
        		$params = array(
        			'type'=>$type,
        			'inviteId'=>$inviteId,
        			'player' => $player,
        			'totalCount' => $totalCount,
        			'playerFriendList'=> $friendList,
        			'cdTime' => $cdTime,
        			'isGuide' => $isGuide,
        			'category' => $category,
        		);
        		$viewFile = 'randomFriend';
        		$data = $this->renderPartial($viewFile, $params, true);
        		break;
        	case 2:
        		$page = 1;        
        		$totalCount = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
        		$id = VisualFriend::checkVisualFriend($this->playerId);
		        if ($id) {
		        	$totalCount++;
		        }
        		$requestList = FriendModel::getFriendRequestList($playerId, FRIEND_INDEX_PAGE_NUM, $page,0);
            	$num = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_CONFIRMING);
				$params = array(
            		'currentPage' => $page,
            		'player' => $player,
					'totalCount' => $totalCount,
					'num' =>$num,
            		'friendRequestList' => $requestList,
					'category' => $category,
        		);
        		$viewFile = 'beFriendList';
        		$data = $this->renderPartial($viewFile, $params, true);
        		break;
        	case 3:
        		$totalCount = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
        		$type = 1;
        		$cdTime = FriendInfo::getCDTime($playerId);
        		if ($cdTime != 0) {
            		$playerFriendList = FriendModel::getRandomFriendById($playerId);
            		$friendList = $playerFriendList['friendList'];
            		$cdTime = $playerFriendList['cdTime'];
            		$params = array(
            			'type'=>$type,
            			'player' => $player,
            			'totalCount' => $totalCount,
            			'playerFriendList' => $friendList,
            			'cdTime' => $cdTime ,
            			'isGuide' => 0,
            			'category' => 2,
            		);
        		}else {
            		$playerFriendList = FriendModel::getRandomFriend($playerId, RANDOM_NUM, RANDOM_NUM2);
            		$friendList = $playerFriendList['friendList'];
            		$cdTime = $playerFriendList['cdTime'];
            		$friendIdList = $playerFriendList['friendIdList'];
            		$idList = serialize($friendIdList);
            		FriendInfo::saveRandomList($playerId, $idList);
            		$params = array(
            			'type'=>$type,
            			'inviteId'=>'',
            			'player' => $player,
            			'totalCount' => $totalCount,
            			'playerFriendList' => $friendList,
            			'cdTime' => $cdTime,
            			'isGuide' => 0
            		);
        		}
        		$viewFile = 'randomFriend';
        		$data = $this->renderPartial($viewFile, $params, true);
        		break;
        	default:
        		throw new SException(Yii::t('View', 'param error'));
        }
        $this->display($data);
    }

    /*发送好友请求*/

    public function actionCreateFriendRequest() {
        if(isset($_GET['friendId'])) {
        	$transaction = Yii::app()->db->beginTransaction();
        	try {
            	$friendId = $_GET['friendId'];
            	$playerId = $this->playerId;
            	FriendModel::createFriendRequest($playerId, $friendId);
            	$transaction->commit();	
        	}
        	catch (Exception $e) {
            	$transaction->rollBack();
            	throw $e;
        	}
        	$this->display(array('playerId'=>$playerId, 'friendId'=>$friendId),array('message' => Yii::t('Friend', 'apply success')));
        }
        else {
        	throw new SException(Yii::t('View', 'param error'));
        }
    }

    /*同意好友请求*/

    public function actionConfirmFriendRequest() {
        $this->actionType = REQUEST_TYPE_AJAX ;
        if(isset($_GET['friendId'])) {
            $friendId = $_GET['friendId'];
            $playerId = $this->playerId;
            FriendModel::confirmFriendRequest($playerId, $friendId);
            $this->display(array('friendId'=>$friendId,'playerId'=>$playerId));
        }
        else {
            throw new SException(Yii::t('View', 'param error'));
        }
    }

    /*拒绝好友请求*/

    public function actionRefuseFriendRequest() {
        $this->actionType = REQUEST_TYPE_AJAX ;
        if(isset($_GET['friendId'])) {
            $friendId = $_GET['friendId'];
            $playerId = $this->playerId;
            FriendModel::refuseFriendRequest($playerId, $friendId);
            $this->display(array('friendId'=>$friendId,'playerId'=>$playerId));
        }
        else {
            throw new SException(Yii::t('View', 'param error'));
        }
    }

    /*删除好友*/

    public function actionDeleteFriend() {
        $this->actionType = REQUEST_TYPE_AJAX ;
        if(isset($_GET['friendId'])) {
            $friendId = $_GET['friendId'];
            $playerId = $this->playerId;
            FriendModel::deleteFriend($playerId, $friendId);
            $this->display(array('friendId'=>$friendId,'playerId'=>$playerId));
        }
        else {
            throw new SException(Yii::t('View', 'param error'));
        }
    }

    /*开启好友请求

    public function actionAddFriendON() {
        $this->actionType = REQUEST_TYPE_AJAX ;
        $playerId = $this->playerId;
        FriendInfo::addFriendON($playerId);
        $this->display();
    }
	*/
    
    /*关闭好友请求

    public function actionAddFriendOFF() {
        $this->actionType = REQUEST_TYPE_AJAX ;
        $playerId = $this->playerId;
        FriendInfo::addFriendOFF($playerId);
        $this->display();
    }
	*/
    
    /*回收种子*/

    public function actionRecycleSeed() {
        if(isset($_GET['friendId']) && isset($_GET['seedId'])) {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $friendId = $_GET['friendId'];
                $seedId = $_GET['seedId'];
                //是否为虚拟好友
                $seed = Yii::app()->objectLoader->load('Seed', $seedId);
                $id = VisualFriend::checkOwnerByGarden($seed->gardenId);
                if ($id) {
                    $seedModel = Yii::app()->objectLoader->load('SeedModel', $this->playerId);
                    $seedModel->fosterSeedBack($seedId);
                    //通过礼物邮箱打回种子
                    $seedMailInfo = array(
                        'playerId' => $seed->playerId,
                        'isGet' => 0,
                        'getDays' => MAIL_DEFAULTVALUE,
                        'keepDays' => MAIL_DEFAULTVALUE,
                        'informType' => MAIL_PLAYERMAIL,
                        'fromId' => $id,
                        'content' => '',
                        'goodsId' => MAIL_DEFAULTVALUE,
                        'seedId' => $seed->seedId,
                        'sentGold' => MAIL_DEFAULTVALUE,
                        'isRead' => 0,
                    ); 
                    if ($seedMailInfo['informType'] == MAIL_PLAYERMAIL) {
                        $seedMailInfo['keepDays'] = time() + 86400 * MAIL_MAXKEEPDAYS;
                    }
                    if (isset($seedMailInfo['seedId']) && $seedMailInfo['seedId'] != 0) {
                        $seed = Yii::app()->objectLoader->load('Seed', $seedMailInfo['seedId']);
                        $seedLevel = $seed->getMyLevel();
                        $seedMailInfo['getDays'] = time() + (int) (3600 * ($seedLevel / 4));
                    }
                    $arrayList = explode(',', $seed->playerId);
                    MailModel::sendMails($seedMailInfo, $arrayList);

                    $key = VisualFriend::createKey($id, $this->playerId);
                    $visualFriend = Yii::app()->objectLoader->load('VisualFriend', $key);
                    $visualFriend->setFosterSeedId();
                } else {
                    $fosterModel = Yii::app()->objectLoader->load('FosterModel', $this->playerId);
                    $fosterModel->seedToMail($seedId, $friendId);
                }

                $transaction->commit();
            }
            catch (Exception $ex) {
                $transaction->rollBack();
                throw $ex;
            }
        }
        $this->display(array('seedId'=>$seedId));
    }

    public function actionRecycleSeeds() {
        $this->actionType = REQUEST_TYPE_AJAX ;
        $transaction = Yii::app()->db->beginTransaction();
        try {
        	$seedIds = FriendModel::getRecycleInfo($this->playerId);
        	//回收虚拟好友种子
        	$id = VisualFriend::checkVisualFriend($this->playerId);
        	if ($id) {
            	$key = VisualFriend::createKey($id, $this->playerId);
            	$visualFriend = Yii::app()->objectLoader->load('VisualFriend', $key);
        	}
        	if (!empty($visualFriend) and $visualFriend->isFoster()) {
            	$seedId = $visualFriend->getFosterSeed()->seedId;
            	$seedModel = Yii::app()->objectLoader->load('SeedModel', $this->playerId);
            	$seedModel->fosterSeedBack($seedId);
            	$seed = Yii::app()->objectLoader->load('Seed', $seedId);
            	$fosterModel = Yii::app()->objectLoader->load('FosterModel', $this->playerId);
            	$fosterModel->createSeedMailInfo($seed, $id);
            	$seedIds[]=$seedId;
            	$visualFriend->setFosterSeedId();
        	}
        	$transaction->commit();
        }
    	catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
        $this->display(array('seedIds'=>$seedIds));
    }

    public function actionFriendSelect() {
        $playerId = $this->playerId;
        $player = $player = Yii::app()->objectLoader->load('Player', $playerId);
        $totalCount = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
        if(isset($_GET['page'])) {
            $page = ceil($_GET['page']);
            if($page < 1) {
                $page = 1;
            }
            $friendList = FriendModel::getFriendInfo($playerId, FRIEND_INDEX_PAGE_NUM, $page,0);
            $totalCount = PlayerFriend::getFriendNum($playerId, FRIEND_STATUS_ACCEPT);
            $displayData =  array('currentPage'=> $page,
                'player' => $player,
                'totalCount' => $totalCount,
                'playerFriendList'=>$friendList,
            );
            //添加符合等级要求的虚拟好友
            if (empty($_REQUEST['noVisual'])) {
                $id = VisualFriend::checkVisualFriend($this->playerId);
                if ($id) {
                    $key = VisualFriend::createKey($id, $this->playerId);
                    $visualFriend = Yii::app()->objectLoader->load('VisualFriend', $key);
                    $totalCount++;
                    $displayData['visualFriend'] = $visualFriend;
                }
            }

            $data = $this->renderPartial('friendSelect', $displayData, true );
        }
        if (isset($_GET['searchId'])) {
            $searchId = $_GET['searchId'];
            $friendList = FriendModel::getFriendInfoByName($playerId, $searchId);
            $displayData = array('currentPage'=> 1,
                'player' => $player,
                'totalCount' => $totalCount,
                'playerFriendList'=>$friendList,
            );
            //检查虚拟好友
            if (empty($_REQUEST['noVisual'])) {
                $id = VisualFriend::checkVisualFriend($this->playerId);
                if ($id) {
                    $key = VisualFriend::createKey($id, $this->playerId);
                    $visualFriend = Yii::app()->objectLoader->load('VisualFriend', $key);
                    if ($visualFriend->playerName==$searchId) {
                        $totalCount++;
                        $displayData['visualFriend'] = $visualFriend;
                    }
                }
            }

            $data = $this->renderPartial('friendSelect', $displayData, true );
        }
        $this->display( $data );
    }

}
