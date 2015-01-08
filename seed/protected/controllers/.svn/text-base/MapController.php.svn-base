<?php
class MapController extends Controller {

    /*进入地图*/
    public function actionIndex() {
        $playerId = $this->playerId;
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        $session = Yii::app()->session;

        $map = Util::loadconfig("mapMessageData");
        if(isset($_GET['mapId'])&&isset($map['areaPoint'][$_GET['mapId']])) {
            $mapId = intval($_GET['mapId']);
        }else {
            if( !($mapId=$player->getStatus('mapId')) ) {
                $mapId = 1;
            }
            $session['explore'] = array();
            $session['notice'] = 0;
        }
        if(isset($_GET['placeId'])) {
            $placeId = intval($_GET['placeId']);
        }

        $placeId = 1;
        $favouriteSeed = $player->getFavouriteSeed();
        if (!empty($favouriteSeed)) {
            $seed = Yii::app()->objectLoader->load('Seed', $favouriteSeed);
        }else {
            $seed = null;
        }

        $info['map'] = $map['areaPoint'][$mapId];
        $info['img'] = $map['areaImg'][$mapId];
        $info['name'] = Yii::t('Map',$map['areaName'][$mapId]);
        if ($mapId>1) {
        	$info['Pname']=Yii::t('Map',$map['areaName'][$mapId-1]);
        }else $info['Pname']=null;
        if ($mapId<5) {
        	$info['Nname']=Yii::t('Map',$map['areaName'][$mapId+1]);
        }else $info['Nname']=null;
        if (!empty($map['areaName'][$mapId+1])){
        	$info['nextName'] = Yii::t('Map',$map['areaName'][$mapId+1]);
        }
        if (!empty($map['areaName'][$mapId-1])){ 
        	$info['PreviouseName'] = Yii::t('Map',$map['areaName'][$mapId-1]);
        }
        $info['actionPoint'] = $map['actionPoint'][$mapId];
        if (isset($map['allowLevel'][$mapId+1])) {
            $info['level'] = $map['allowLevel'][$mapId+1];
        }else {
            $info['level'] = null;
        }
        if ($player->level < $map['allowLevel'][$mapId]) {
            $message = Yii::t('Map', 'allowLevel',array('{level}' => $map['allowLevel'][$mapId]));
            throw new SException($message);
        }else {
        	$player->setStatus('mapId',$mapId);
        }
        $item = Yii::app()->objectLoader->load('Item', $playerId);
        $full = $item->isPileFull();
        if ($full) {
            Yii::app()->objectLoader->load('GlobalMessage', $this->playerId)->addMessage('mapNotice', MESSAGE_TYPE_ITEM_FULL);
            $session['notice'] = 1;
        }
        $notice = 0;
        $test = array();
        $explore = !empty($session['explore'])?$session['explore']:array();
        $messageOld = empty($explore)?'':end($explore);
        $playerinfo = WorldMap::playerInfo($player);
        //新手引导
        $guideModel = Yii::app()->objectLoader->load('GuideModel',$this->playerId);
        $isGuide = $guideModel->isCurrentGuide(90); //90 is for explore
		
        
        $this->display('index', array('player' => $player,'playerinfo'=>$playerinfo, 'seed' => $seed, 'mapId' => $mapId, 'placeId' =>$placeId, 'info'=> $info, 'messageOld'=>$messageOld, 'notice'=>$notice, 'isGuide'=>$isGuide));
    }
    
    /*Ajax切地图*/
    public function actionAjaxShow() {
    	$this->layout = "//layouts/theme";
    	$playerId = $this->playerId;
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        $session = Yii::app()->session;

        $map = Util::loadconfig("mapMessageData");
        if(isset($_REQUEST['mapId'])&&isset($map['areaPoint'][$_REQUEST['mapId']])) {
            $mapId = intval($_REQUEST['mapId']);
        }else {
            if( !($mapId=$player->getStatus('mapId')) ) {
                $mapId = 1;
            }
            $session['explore'] = array();
            $session['notice'] = 0;
        }
        if(isset($_GET['placeId'])) {
            $placeId = intval($_GET['placeId']);
        }

        $placeId = 1;
        $favouriteSeed = $player->getFavouriteSeed();
        if (!empty($favouriteSeed)) {
            $seed = Yii::app()->objectLoader->load('Seed', $favouriteSeed);
        }else {
            $seed = null;
        }

        $info['map'] = $map['areaPoint'][$mapId];
        $info['img'] = $map['areaImg'][$mapId];
        $info['name'] = Yii::t('Map',$map['areaName'][$mapId]);
        if ($mapId>1) {
        	$info['Pname']=Yii::t('Map',$map['areaName'][$mapId-1]);
        }else $info['Pname']=null;
        if ($mapId<5) {
        	$info['Nname']=Yii::t('Map',$map['areaName'][$mapId+1]);
        }else $info['Nname']=null;
        if (!empty($map['areaName'][$mapId+1])){
        	$info['nextName'] = Yii::t('Map',$map['areaName'][$mapId+1]);
        }
        if (!empty($map['areaName'][$mapId-1])){ 
        	$info['PreviouseName'] = Yii::t('Map',$map['areaName'][$mapId-1]);
        }
        $info['actionPoint'] = $map['actionPoint'][$mapId];
        if (isset($map['allowLevel'][$mapId+1])) {
            $info['level'] = $map['allowLevel'][$mapId+1];
        }else {
            $info['level'] = null;
        }
        if ($player->level < $map['allowLevel'][$mapId]) {
        	$message = Yii::t('Map', 'allowLevel',array('{level}' => $map['allowLevel'][$mapId]));
            throw new SException($message);
        }else {
        	$player->setStatus('mapId',$mapId);
        }
        $notice = 0;
        $test = array();
        $explore = !empty($session['explore'])?$session['explore']:array();
        $messageOld = empty($explore)?'':end($explore);
        $playerinfo = WorldMap::playerInfo($player);
        //新手引导
        $guideModel = Yii::app()->objectLoader->load('GuideModel',$this->playerId);
        $isGuide = $guideModel->isCurrentGuide(90); //90 is for explore
		$params = array(
			'player' => $player,
			'playerinfo'=>$playerinfo,
			'seed' => $seed,
			'mapId' => $mapId,
			'placeId' =>$placeId,
			'info'=> $info,
			'messageOld'=>$messageOld,
			'notice'=>$notice,
			'isGuide'=>$isGuide,
		);
		$viewFile = 'index';
        $data = $this->renderPartial($viewFile, $params, true);
        $this->display($data);
    }

    /*探索*/

    public function actionExplore() {
    	$transaction = Yii::app()->db->beginTransaction();
    	 try {
        	$session = Yii::app()->session;
        	$this->actionType = REQUEST_TYPE_AJAX ;
        	if(isset($_GET['Id'])) {
            	$Id = $_GET['Id'];
	            $playerId = $this->playerId;
    	        $player = Yii::app()->objectLoader->load('Player', $playerId);
        	    $r = explode("0",$Id);
            	$exploreData = Util::loadconfig("ExploreData");
	            $actionPoint = $exploreData['actionPoint'][$r[0]];
    	        $Exp = $exploreData['Exp'][$r[0]];
        	    $info = $exploreData['explore'][$Id];
            	$type = 1;
    	        $notice = 0;
	            $explore = null;
        	    $message = null;
            	$messageOld = null;
	            /*if ($player->getPlayerPoint('actionPoint')->getValue() < $actionPoint) {
    	            $type = 3;
        	    }else {*/
                $player->getPlayerPoint('actionPoint')->subValue($actionPoint);
                $player->addExp($Exp);
                $exploreList = WorldMap::exploreItem($playerId, $info);
                if (empty($exploreList['type'])) {
                    $type = 2;
                }
				$id = empty($exploreList['id'])?'null':$exploreList['id'];
				$time = time();
                if( $exploreList['type'] =='res'||$exploreList['type'] =='deco' ) {
                    $data = Yii::app()->objectLoader->load('ItemMeta',$id)->getImagePath();
                }
                elseif( $exploreList['type'] =='seed' ) {
                    $data = Yii::app()->objectLoader->load('Seed',$id)->getDisplayData(false);
                }
                else {
                    $data = '';
                }
				$message = array('actionPoint'=>$actionPoint, 'Exp'=>$Exp, 'type'=>$exploreList['type'], 'id'=>$id, 'data'=>$data, 'time'=>$time);
                $explore = !empty($session['explore'])?$session['explore']:array();
                $messageOld = empty($explore)?'':end($explore);
                if (count($explore)>=20) {
                    array_shift($explore);
                }
                array_push($explore,$message);
                $session['explore'] = $explore;
                if ( empty($session['notice']) ) {
                    $item = Yii::app()->objectLoader->load('Item', $playerId);
                    $full = $item->isPileFull();
                    if ($full) {
                    	Yii::app()->objectLoader->load('GlobalMessage', $this->playerId)->addMessage('resItem', MESSAGE_TYPE_ITEM_FULL2);
                        $session['notice'] = 1;
                    }
                }
                $notice = 0;	
                //成就检查
                $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_MAPEXPLORE, array('mapId'=>$r[0]));
                $achieveEvent->saveProcess($Id);
                $achieveEvent->onAchieveComplete();
                //任务检查
                $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_EXPLORE, array('mapId'=>$r[0]));
                $missionEvent->onMissionComplete();
                //任务检查
                switch ($exploreList['type']) {
                case 'res':
                    //任务检查
                    $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_RESOURCE, array('mapId'=>$r[0], 'itemId'=>$exploreList['id']));
                    $missionEvent->onMissionComplete();
                    //任务检查
                    $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_RESOURCEEXPLORE, array('mapId'=>$r[0], 'itemId'=>$exploreList['id']));
                    $missionEvent->onMissionComplete();
                    break;
                case 'seed':
                    //任务检查
                    $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_SEEDEXPLORE, array('mapId'=>$r[0], 'seedId'=>$exploreList['id']));
                    $missionEvent->onMissionComplete();
                    break;

                default:
                    // code...
                    break;
                }
            	//}
            	$newplayer = Yii::app()->objectLoader->load('Player', $playerId);
            	$playerinfo = WorldMap::playerInfo($newplayer);
            	$notice = 0; //不要原有notice
            	$transaction->commit();
        	}
        }
        catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        $this->display(array('player'=>$newplayer,'playerinfo'=>$playerinfo, 'areaId'=>$Id,'message'=>$message,'messageOld'=>$messageOld,'notice'=>$notice,'type'=>$type));
    }

    /*探索记录*/
    public function actionLog() {
        $session = Yii::app()->session ;
        $explore = $session['explore'];
        $data = $this->renderPartial('log', array('explore' => $explore), true );
        $this->display( $data );
    }

    /*提示
    public function actionNotice() {
        $this->actionType = REQUEST_TYPE_AJAX ;
        $type = $_GET['type'];
        $playerId = $this->playerId;
        $playerItem = Yii::app()->objectLoader->load('Item',$playerId);

        $numall = $playerItem->getItemNum( array(38) );
        $numhalf = $playerItem->getItemNum( array(37) );
        $restoreAllGoods = Yii::app()->objectLoader->load('ShopGoods',90001);
        $restoreHalfGoods = Yii::app()->objectLoader->load('ShopGoods',90002);
        $params = array(
            'type' => $type,'num1'=>$numall,'num2'=>$numhalf ,
            'restoreAllGoods'   => $restoreAllGoods ,
            'restoreHalfGoods'  => $restoreHalfGoods ,
        ) ;
        $data = $this->renderPartial('notice', $params, true );
        $this->display( $data );
    }
    */
    
    public function actionAp() {
    	$this->actionType = REQUEST_TYPE_AJAX ;
    	throw new SException('action point is not enough', EXCEPTION_TYPE_AP_NOT_ENOUGH);
    }
    
    public function actionGetAP() {
    	$playerId = $this->playerId;
    	$player = Yii::app()->objectLoader->load('Player', $playerId);
    	$playerinfo['AP'] = $player->getPlayerPoint('actionPoint')->getValue();
        $playerinfo['APMAX'] = $player->getPlayerPoint('actionPoint')->getMax();
        $playerinfo['AP2'] = ceil(($playerinfo['AP']/$playerinfo['APMAX'])*100);
        $this->display( array('playerinfo' => $playerinfo ));
    }
    
	public function actionItem() {
    	$this->actionType = REQUEST_TYPE_AJAX ;
    	throw new SException('action point is not enough', EXCEPTION_TYPE_ITEM_FULL);
    }
}
?>
