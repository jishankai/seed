<?php

class PlayerFriend extends RecordModel {
	private static $keyConnector = '_';
	private $db;
	private $playerId;
	private $friendId;
	public $uniqueKey;
	
	public function __construct($uniqueKey) {
        $this->db = Yii::app()->db;
        $paramArray = explode(self::$keyConnector, $uniqueKey);
        $this->playerId = $paramArray[0];
        $this->friendId = $paramArray[1];
        $this->uniqueKey = $uniqueKey;
        //parent::__construct($uniqueKey);
    }
		
	public static function makeKey($playerId, $friendId) {
        return $playerId . self::$keyConnector . $friendId;
    }
	
	public static function attributeColumns(){
        return array(
            'playerId', 'friendId', 'status', 'fosterSeed', 'statusChangeTime', 'powerChangeTime'
        );
    }
    
    public function attributeNames() {
        return self::attributeColumns();
    }
	
	/*好友请求*/
	
	public static function createFriendRequest($playerId, $friendId) {
		self::create(array('playerId' => $playerId, 'friendId' => $friendId, 'status' => FRIEND_STATUS_APPLYING));
		self::create(array('playerId' => $friendId, 'friendId' => $playerId, 'status' => FRIEND_STATUS_CONFIRMING));
		self::statusChange($playerId, $friendId);
		self::statusChange($friendId, $playerId);
    }
	
	public static function create($createInfo) {
        $insertArr = array();
		foreach (self::attributeColumns() as $key) {
            if(isset($createInfo[$key])){
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['createTime'] = time();
        return DbUtil::insert(Yii::app()->db, 'friend', $insertArr, true);
    }
	
	public function loadData() {
        $command = $this->db->createCommand('SELECT * FROM friend WHERE playerId = :playerId AND friendId = :friendId');
        $command->bindParam(':playerId', $this->playerId);
        $command->bindParam(':friendId', $this->friendId);
        return $command->queryRow();
    }
	
	public function saveData($attributes = array()) {
        DbUtil::update($this->db, 'friend', $attributes, array('playerId' => $this->playerId, 'friendId' => $this->friendId));
    }
	
	/*删除好友*/
	
	public function deleteData() {
        $command = $this->db->createCommand('DELETE FROM friend WHERE playerId = :playerId AND friendId = :friendId');
        $command->bindParam(':playerId', $this->playerId);
        $command->bindParam(':friendId', $this->friendId);
        $command->execute();
        $this->reset();
    }
	
	public static function loadDatas($ids = array()) {
    }
	
	public static function multiLoad( $params=array(),$isSimple=true ){
	}
	
	/*获取玩家好友列表*/
	
	public static function getFriendId($playerId, $status, $numPerPage = null, $page = null, $num) {
		$sql = 'SELECT * FROM friend WHERE playerId = :playerId AND status = :status ORDER BY fosterSeed DESC, statusChangeTime ASC';
        if (isset($numPerPage) and isset($page)) {
            $startIndex = ($numPerPage * ($page - 1)-$num);
            $sql .= ' LIMIT ' . $startIndex . ', ' . $numPerPage;
        }
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':playerId', $playerId);
        $command->bindParam(':status', $status);
        $rowArray = $command->queryAll();
        $detailList = array();
        foreach ($rowArray as $row) {
            $playerFriend = Yii::app()->objectLoader->load('PlayerFriend', self::makeKey($playerId, $row['friendId']));
            //$playerFriend->loadRecord($row);
            array_push($detailList, $playerFriend);
        }
        return $detailList;
	}
	
	public static function getFosterFriend($playerId) {
		$sql = 'SELECT * FROM friend WHERE playerId = :playerId AND fosterSeed IS NOT NULL';
		$command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':playerId', $playerId);
		$rowArray = $command->queryAll();
        $detailList = array();
		if (!empty($rowArray)) {
			foreach ($rowArray as $row) {
            	array_push($detailList, array('seedId'=>$row['fosterSeed'], 'friendId'=>$row['friendId']));
        	}
		}
		return $detailList;
	}
	
	public static function getFosterNum($playerId) {
		$sql = 'SELECT count(*) FROM friend WHERE playerId = :playerId AND fosterSeed IS NOT NULL';
		$command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':playerId', $playerId);
		return $command->queryScalar();
	}
	
	public static function getFriendIdByName($searchId) {
		$command = Yii::app()->db->createCommand('SELECT playerId FROM player WHERE playerName = :playerName');
		$command->bindParam(':playerName', $searchId);
		return $command->queryScalar();
	}
	
	/*获取玩家好友人数*/
	
	public static function getFriendNum($playerId, $status) {
		$command = Yii::app()->db->createCommand('SELECT count(*) FROM friend WHERE playerId = :playerId AND status = :status');
		$command->bindParam(':playerId', $playerId);
        $command->bindParam(':status', $status);
        return $command->queryScalar();
	}
	
	/*获取数组中好友的信息*/
	
	public static function getplayerInfoList($i,$numPerPage,$playerList) {
		$friendList = array();
		$num = count($playerList);
		$min = ($i-1)*$numPerPage;
		$max = $i*$numPerPage;
		for ( $j=0; $j<$num; $j++) {
			if ( $j>=$min && $j<$max) {
				$player = Yii::app()->objectLoader->load('Player',$playerList[$j]);
				$favouriteSeed = $player->getFavouriteSeed();
				if (!empty($favouriteSeed)) {
					$seed = Yii::app()->objectLoader->load('Seed', $favouriteSeed);
					$seedData = $seed->getDisplayData();
				}else {
					$seedData = 'self';
				}
				array_push($friendList, array('player' => $player, 'seed' => $seedData));
			}
			else {
				$player = Yii::app()->objectLoader->load('Player',$playerList[$j]);
				array_push($friendList, array('player' => $player,));
			}
		}
		return $friendList;
	}
	
	/*获取符合条件的随即玩家*/
	
	public static function getRandomFriendId1($playerLv) {
		$max = ceil($playerLv/10)*10;
		$min = $max-9;
		$sql = 'SELECT * FROM player WHERE level BETWEEN :min AND :max ORDER BY updateTime DESC';
        $command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':min', $min);
		$command->bindParam(':max', $max);
        return $command->queryColumn();
	}
	
	public static function getRandomFriendId2($playerLv) {
		$max = ceil($playerLv/10)*10;
		$min = $max-9;
		$sql = 'SELECT * FROM player WHERE level NOT BETWEEN :min AND :max ORDER BY updateTime DESC';
        $command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':min', $min);
		$command->bindParam(':max', $max);
        return $command->queryColumn();
	}
	
	/*获取符合招待ID的玩家*/
	public static function getPlayerByInviteId($inviteId) {
		$sql = 'SELECT * FROM player WHERE inviteId = :inviteId ORDER BY updateTime DESC';
		$command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':inviteId', $inviteId);
        return $command->queryColumn();
	}
		
	/*判断是否为好友 0为好友 1为申请 2为代确认 3为刚删除好友关系 4为非好友 5为自己 */
	
	public static function isFriend($playerId,$friendId) {
		if ($playerId == $friendId) {
			return 5;
        }
        //判断是否为虚拟好友
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        foreach (Util::loadConfig('VisualFriendLevelStage') as $id=>$data) {
            if ($player->level>=$data['show'] and $player->level<$data['hide']) {
                if ($id == $friendId) {
                    return 0;
                }
            }
        }

		$sql = 'SELECT status FROM friend WHERE playerId = :playerId AND friendId = :friendId';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':playerId', $playerId);
        $command->bindParam(':friendId', $friendId);
        if ($command->queryColumn()) {
			$column = $command->queryColumn();
			return $column[0];
		}else {
			return 4;
		}
	}
	
	/*判断是否能访问 0为自己 1为好友 2为随机好友 3为非好友*/
	
	public static function abletoVisit($playerId,$friendId) {
		if ($playerId == $friendId) {
			return 0;
		}
		$isfriend = self::isFriend($playerId,$friendId);
		if ($isfriend == 0) {
			return 1;
		}else {
			$randomList = FriendInfo::getRandomList($playerId);
			if (!empty($randomList)) {
				$RandomList = unserialize($randomList);
				foreach ($RandomList as $Random) {
					if ($Random['id'] == $friendId) return 2;
				}
				return 3;
			}else {
				return 3;
			}
		}
	}
	
	public static function powerTime($playerId, $friendId) {
		if ($playerId == $friendId) {
			throw new SException("SELF");
		}
		else{
			$isfriend = self::isFriend($playerId,$friendId);
			if ($isfriend == 0) {
				return self::powerChangeTime($playerId,$friendId);
			}else {
				$randomList = FriendInfo::getRandomList($playerId);
				if (!empty($randomList)) {
					$RandomList = unserialize($randomList);
					foreach ($RandomList as $Random) {
						if ($Random['id'] == $friendId) return $Random['pC'];
					}
					throw new SException("NOT EXIST");
				}
				throw new SException("NOT EXIST");
			}
		}
	}

    public static function isChargedToday($playerId, $friendId)
    {
         $lastChargeTime = PlayerFriend::powerTime($playerId, $friendId);
         $today = strtotime(date('Y-m-d 00:00:00'));
         return $lastChargeTime>$today;
    }
	
	public static function powerChange($playerId, $friendId, $time) {
		if ($playerId == $friendId) {
			throw new SException("SELF");
		}
		else{
			$exist = 0;
			$isfriend = self::isFriend($playerId,$friendId);
			if ($isfriend == 0) {
				self::powerTimeChange($playerId,$friendId,$time);
				$exist = 1;
			}else {
				$randomList = FriendInfo::getRandomList($playerId);
				if (!empty($randomList)) {
					$RandomList = unserialize($randomList);
					foreach ($RandomList as &$Random) {
						if ($Random['id'] == $friendId) {
							$Random['pC'] = $time;
							$exist = 1;
						}
					}
					$randomList = serialize($RandomList);
					FriendInfo::saveRandomList($playerId, $randomList);
				}
			}
			if ($exist == 0) throw new SException("NOT EXIST");
			else return 0;
		}
	}
	
	/*状态改变时间*/
	
	public static function statusChange($playerId, $friendId) {
		$sql = 'UPDATE friend SET statusChangeTime = updateTime WHERE playerId = :playerId AND friendId = :friendId';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':playerId', $playerId);
		$command->bindParam(':friendId', $friendId);
		$command->execute();
	}
	
	/*充能改变时间*/
	
	public static function powerChangeTime($playerId, $friendId) {
		$sql = 'SELECT powerChangeTime FROM friend WHERE playerId = :playerId AND friendId = :friendId';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':playerId', $playerId);
        $command->bindParam(':friendId', $friendId);
        return $command->queryScalar();
	}
	
	public static function powerTimeChange($playerId, $friendId, $time) {
		$sql = 'UPDATE friend SET powerChangeTime = :time WHERE playerId = :playerId AND friendId = :friendId';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':time', $time);
		$command->bindParam(':playerId', $playerId);
		$command->bindParam(':friendId', $friendId);
		$command->execute();
	}
	
	/*寄养种子信息相关*/
	public static function addFosterSeed($friendId, $seedId) {
		$sql1 = 'SELECT playerId FROM seed WHERE seedId = :seedId';
		$command = Yii::app()->db->createCommand($sql1);
		$command->bindParam(':seedId', $seedId);
		$playerId = $command->queryScalar();
		$sql = 'UPDATE friend SET fosterSeed = :fosterSeed WHERE playerId = :playerId AND friendId = :friendId';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':fosterSeed', $seedId);
		$command->bindParam(':playerId', $playerId);
		$command->bindParam(':friendId', $friendId);
		$command->execute();
		
		//成就检查
		$achieveEvent = new AchievementEvent($friendId, ACHIEVEEVENT_FOSTERED);
		$achieveEvent->onAchieveComplete();
	}
	
	public static function recycleFosterSeed($friendId, $seedId) {
		$sql1 = 'SELECT playerId FROM seed WHERE seedId = :seedId';
		$command = Yii::app()->db->createCommand($sql1);
		$command->bindParam(':seedId', $seedId);
		$playerId = $command->queryScalar();
		$sql = 'UPDATE friend SET fosterSeed = null WHERE playerId = :playerId AND friendId = :friendId';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':playerId', $playerId);
		$command->bindParam(':friendId', $friendId);
		$command->execute();
	}
	
	public static function moreFriendInfo($friendList,$page) {
    	$Pmessage='';
		$figure=array();
        if (!empty($friendList['foster'])) {
            foreach ($friendList['foster'] as $friend) {
			$Pmessage=$Pmessage.'<li id="friend'.$friend['friend']->playerId.'"><div class="a_bigpic"><div id="figure'.$friend['friend']->playerId.'" style="margin:40px 0 0 40px;"></div></div>';		
			$Pmessage=$Pmessage.'<script>';
			if (empty($friend['seed'])) {
				array_push($figure,array('type'=>1,'id'=>$friend['friend']->playerId,'data'=>null));
			}else {
				array_push($figure,array('type'=>2,'id'=>$friend['friend']->playerId,'data'=>$friend['seed']->getDisplayData()));
			}
			$Pmessage=$Pmessage.'</script>';
			$Pmessage=$Pmessage.'<p><em id="Name'.$friend['friend']->playerId.'">'.$friend['friend']->playerName.'</em><i>lv '.$friend['friend']->level.'</i></p><span class="fosterSeed">';
			if (!empty($friend['fosterSeedId'])) {
				$seedId = $friend['fosterSeedId'];
				$Pmessage=$Pmessage.'<a href="#" class="a_btn10" onclick="CommonFriend.Rec('.$friend['friend']->playerId.','.$seedId.')"><span id="seed'.$seedId.'" style="margin-left:30px; margin-top:25px; float:left;"></span></a>';
				$Pmessage=$Pmessage.'<script>';
				$Pmessage=$Pmessage.'SeedUnit("seed'.$seedId.'",'.$friend['fosterSeed']->getDisplayData().',0.45);';
				$Pmessage=$Pmessage.'</script>';
			}
            else {
                $Pmessage .= '<span class="fosterSeed"><div style="float:left;width:165px;height:108px;"></div></span>';
            }
			$Pmessage=$Pmessage.'<div class="friend_btn"><a href="#" class="a_btn11 a_ico42" onclick="NativeApi.callback({action:\'visit\',playerId:\''.$friend['friend']->playerId.'\'});"><img src="themes/images/imga/a_ico42.png" /></a>';
			$Pmessage=$Pmessage.'<a href="#" class="a_btn11 a_ico47" onclick="CommonFriend.Del('.$friend['friend']->playerId.')"><img src="themes/images/imgb/pic_47.png" /></a></div>';
			$Pmessage=$Pmessage.'</li>';
		}
        }
        if (!empty($friendList['nofoster'])) {
            foreach ($friendList['nofoster'] as $friend) {
			$Pmessage=$Pmessage.'<li id="friend'.$friend['friend']->playerId.'"><div class="a_bigpic"><div id="figure'.$friend['friend']->playerId.'" style="margin:40px 0 0 40px;"></div></div>';		
			$Pmessage=$Pmessage.'<script>';
			if (empty($friend['seed'])) {
				array_push($figure,array('type'=>1,'id'=>$friend['friend']->playerId,'data'=>null));
			}else {
				array_push($figure,array('type'=>2,'id'=>$friend['friend']->playerId,'data'=>$friend['seed']->getDisplayData()));
			}
			$Pmessage=$Pmessage.'</script>';
			$Pmessage=$Pmessage.'<p><em id="Name'.$friend['friend']->playerId.'">'.$friend['friend']->playerName.'</em><i>lv '.$friend['friend']->level.'</i></p>';
			if (!empty($friend['fosterSeedId'])) {
				$seedId = $friend['fosterSeedId'];
				$Pmessage=$Pmessage.'<a href="#" class="a_btn10"><span id="seed'.$seedId.'" style="margin-left:20px; margin-top:10px; float:left;"></span></a>';
				$Pmessage=$Pmessage.'<script>';
				$Pmessage=$Pmessage.'$(function(){SeedUnit("seed'.$seedId.'",'.$friend['fosterSeed']->getDisplayData().',0.5);});';
				$Pmessage=$Pmessage.'</script>';
			}
            else {
                $Pmessage .= '<span class="fosterSeed"><div style="float:left;width:165px;height:108px;"></div></span>';
            }
			$Pmessage=$Pmessage.'<div class="friend_btn"><a href="#" class="a_btn11 a_ico42" onclick="NativeApi.callback({action:\'visit\',playerId:\''.$friend['friend']->playerId.'\'});"><img src="themes/images/imga/a_ico42.png" /></a>';
			$Pmessage=$Pmessage.'<a href="#" class="a_btn11 a_ico47" onclick="CommonFriend.Del('.$friend['friend']->playerId.')"><img src="themes/images/imgb/pic_47.png" /></a></div>';
			$Pmessage=$Pmessage.'</li>';
		}
        }

        /*
		if ($Pmessage!='') {
			$Pmessage=$Pmessage.'<ul class="a_list2 w_871" id="list'.($page+1).'"><li><p style="" id="more" onclick="CommonFriend.More()" >More</p><p id="nomore" style="display:none;">NoMore</p></li></ul>';
        }
         */
		return array('Pmessage'=>$Pmessage, 'figure'=>$figure);
    }
    
	public static function moreFriendInfo2($friendList,$page) {
		$Pmessage='';
        $figure=array();
        if (!empty($friendList['foster'])) {
            	foreach ($friendList['foster'] as $friend) {
			$Pmessage=$Pmessage.'<li style="cursor:pointer;" onclick="selectFriend.over('.$friend['friend']->playerId.')"><div class="a_bigpic"> <div id="figure'.$friend['friend']->playerId.'" style="margin:40px 0 0 40px;"></div></div></div>';		
			$Pmessage=$Pmessage.'<script>';
			if (empty($friend['seed'])) {
				array_push($figure,array('type'=>1,'id'=>$friend['friend']->playerId,'data'=>null));
                $Pmessage.='$(\'#figure'.$friend['friend']->playerId.'\').parent().html(\'<img src="themes/img/pic_03.png" height="100" width="75"/>\');';
			}else {
				array_push($figure,array('type'=>2,'id'=>$friend['friend']->playerId,'data'=>$friend['seed']->getDisplayData()));
                $Pmessage.='SeedUnit("figure'.$friend['friend']->playerId.'",'.$friend['seed']->getDisplayData().',0.5);';
			}
			$Pmessage=$Pmessage.'</script>';
			$Pmessage=$Pmessage.'<p><em>'.$friend['friend']->playerName.'</em><i>lv '.$friend['friend']->level.'</i></p>';
			$Pmessage=$Pmessage.'</li>';
		}
        }
	if (!empty($friendList['nofoster'])) {
            	foreach ($friendList['nofoster'] as $friend) {
			$Pmessage=$Pmessage.'<li style="cursor:pointer;" onclick="selectFriend.over('.$friend['friend']->playerId.')"><div class="a_bigpic"> <div id="figure'.$friend['friend']->playerId.'" style="margin:40px 0 0 40px;"></div></div></div>';		
			$Pmessage=$Pmessage.'<script>';
			if (empty($friend['seed'])) {
				array_push($figure,array('type'=>1,'id'=>$friend['friend']->playerId,'data'=>null));
                $Pmessage.='$(\'#figure'.$friend['friend']->playerId.'\').parent().html(\'<img src="themes/img/pic_03.png" height="100" width="75"/>\');';
			}else {
				array_push($figure,array('type'=>2,'id'=>$friend['friend']->playerId,'data'=>$friend['seed']->getDisplayData()));
                $Pmessage.='SeedUnit("figure'.$friend['friend']->playerId.'",'.$friend['seed']->getDisplayData().',0.5);';
			}
			$Pmessage=$Pmessage.'</script>';
			$Pmessage=$Pmessage.'<p><em>'.$friend['friend']->playerName.'</em><i>lv '.$friend['friend']->level.'</i></p>';
			$Pmessage=$Pmessage.'</li>';
		}
        }
		
		return array('Pmessage'=>$Pmessage, 'figure'=>$figure);
    }
    
	public static function moreFriendInfo3($requestList,$page) {
		$Pmessage='';
		$figure=array();
		foreach ($requestList as $friend) {
			$Pmessage=$Pmessage.'<li id="friend'.$friend['friend']->playerId.'"><div class="a_bigpic"> <div id="figure'.$friend['friend']->playerId.'" style="margin:40px 0 0 40px;"></div></div></div>';		
			$Pmessage=$Pmessage.'<script>';
			if (empty($friend['seed'])) {
				array_push($figure,array('type'=>1,'id'=>$friend['friend']->playerId,'data'=>null));
			}else {
				array_push($figure,array('type'=>2,'id'=>$friend['friend']->playerId,'data'=>$friend['seed']->getDisplayData()));
			}
			$Pmessage=$Pmessage.'</script>';
			$Pmessage=$Pmessage.'<p><em id="Name'.$friend['friend']->playerId.'">'.$friend['friend']->playerName.'</em><i>lv '.$friend['friend']->level.'</i></p>';
			$Pmessage=$Pmessage.'<a id="confirm'.$friend['friend']->playerId.'" href="#" class="a_btn11 ml5 a_ico44" onclick="CommonFriend.Confirm('.$friend['friend']->playerId.')"><span></span></a>';
			$Pmessage=$Pmessage.'<a id="refuse'.$friend['friend']->playerId.'" href="#" class="a_btn11 a_ico47" onclick="CommonFriend.Refuse('.$friend['friend']->playerId.')"><span></span></a>';
			$Pmessage=$Pmessage.'</li>';
		}
		
		return array('Pmessage'=>$Pmessage, 'figure'=>$figure);
    }
    
    public static function visualFriendInfo($visualFriend) {
    	$Pmessage='';
    	$Pmessage=$Pmessage.'<li><div class="a_bigpic"> <div id="figure'.$visualFriend->id.'" style="margin:40px 0 0 40px;"></div></div>';
    	$Pmessage=$Pmessage.'<script>';
    	if (empty($visualFriend->favoriteSeed)) {
    		$figure['type']=1;
    		$figure['id']=$visualFriend->id;
    		$figure['id2']=$visualFriend->defaultGarden;
    		$figure['data']=null;
    	}else {
    		$figure['type']=2;
    		$figure['id']=$visualFriend->id;
    		$figure['id2']=$visualFriend->defaultGarden;
    		$figure['data']=$visualFriend->getFavoriteSeed()->getDisplayData();
    	}
    	$Pmessage=$Pmessage.'</script>';
    	$Pmessage=$Pmessage.'<p><em>'.$visualFriend->playerName.'</em><i>lv '.$visualFriend->level.'</i></p>';
    	$Pmessage=$Pmessage.'<a href="#" class="a_btn11 a_ico42"><img src="themes/images/imga/a_ico42.png" /></a>';
    	$Pmessage=$Pmessage.'<a id="createVisualFriend" href="#" class="a_btn11 a_ico44"><span class="createRequest" id="'.$visualFriend->id.'"></span></a>';
    	$Pmessage=$Pmessage.'</li>';
    	return array('Pmessage'=>$Pmessage, 'figure'=>$figure);
    }
}
