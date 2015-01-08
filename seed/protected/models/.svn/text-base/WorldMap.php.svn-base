<?php

class WorldMap extends Model {
	private $player;
	private $playerId;
	
	public function __construct($playerId) {
		$this->playerId = $playerId;
	}
	
	public static function exploreItem($playerId, $info) {
		$probability = $info['Probability'];
		$exploreItem = $info['exploreItem'];
		$r = rand(0,9999);
		$num = 0;
		for ($i=0; $i<4; $i++) {
			$num+= $probability[$i];
			if ($r < $num) break;
		}
		$exploreBox = $exploreItem[$i];
		$kind = array_keys($exploreBox);
		switch ($kind[0]) {
			case "gold":
				$gold = $exploreBox['gold'];
				$player = Yii::app()->objectLoader->load('Player', $playerId);
				$player->addGold($gold);
				$itemList = array('type'=>'gold', 'id'=>$gold);
				break;
			case "resItem":
				$dropData = Util::loadconfig("dropData");
				$dropId = $exploreBox['resItem'];
				$dropbox = $dropData['drop'][$dropId];
				$rr = rand(0,9999);
				$num = 0;
				$kind = $dropbox['kind'];
				for ($i=0; $i<$kind; $i++) {
					$num+= $dropbox['Probability'][$i];
					if ($rr < $num) break;
				}
				$itemId = $dropbox['item'][$i];

				$itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
				$playerItem = Yii::app()->objectLoader->load('Item',$playerId);
				$playerItem -> addItem( $itemMeta, 'explore',1,false);
				$itemList = array('type'=>'res', 'id'=>$itemId);

				break;
				
			case "decoItem":
				$dropData = Util::loadconfig("dropData");
				$dropId = $exploreBox['decoItem'];
				$dropbox = $dropData['drop'][$dropId];
				$rr = rand(0,9999);
				$num = 0;
				$kind = $dropbox['kind'];
				for ($i=0; $i<$kind; $i++) {
					$num+= $dropbox['Probability'][$i];
					if ($rr < $num) break;
				}
				$itemId = $dropbox['item'][$i];

					$itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
					$playerItem = Yii::app()->objectLoader->load('Item',$playerId);
					$playerItem -> addItem( $itemMeta, 'explore',1,false);
					$itemList = array('type'=>'deco', 'id'=>$itemId);

				break;
				
			case "seed":
				$mailInfo = array();
				$seedModel = Yii::app()->objectLoader->load('SeedModel',$playerId);
				$seed = $seedModel->generateSeed2(SEED_FROM_MAP_EXPLORE,$exploreBox['seed']['min'],$exploreBox['seed']['max']);
				$mailInfo['seedId'] = $seed->seedId;
				$mailInfo['fromId'] = MAIL_DEFAULTFROMID;
				$mailInfo['informType'] = MAIL_SYSTEMMAIL;
                $mailInfo['content'] = '[%newSeedMail%]';
				$user = array($playerId);
				MailModel::sendMails($mailInfo, $user);
				$itemList = array('type'=>'seed', 'id'=>$seed->seedId);
				break;
			default:
				$itemList = array('type'=>null);
				break;
		}
		return $itemList;
	}
	
	public static function checkExploreTimes($array,$Id) {
		$num = 0;
		if (!empty($array)) {
			foreach ($array as $ids) {
				$r = explode("0",$ids);
				$id = $r[0];
				if ($id == $Id) {
					$num++;
				}
			}
		}
		return $num;
	}
	
	public static function checkExploreAll ($array) {
		$List = array_unique($array);
		$num = count($List);
		if ($num >= MapExplorePoint) return true;
		else return false;
	}
	
	public static function playerInfo ($player) {
		$playerinfo['level'] = $player->level;
        $playerinfo['EXP'] = $player->level<100? $player->exp:'MAX';
        $playerinfo['NEXP'] = $player->level<100?$player->nextLevelExp():'MAX';
        if ($playerinfo['EXP']=='MAX') {
       		$playerinfo['EXP2']=100;
       	}else {
	       	$playerinfo['EXP2']=($playerinfo['EXP']/$playerinfo['NEXP'])*100;
   	    }
       	$playerinfo['AP'] = $player->getPlayerPoint('actionPoint')->getValue();
        $playerinfo['APMAX'] = $player->getPlayerPoint('actionPoint')->getMax();
    	$playerinfo['AP2'] = ceil(($playerinfo['AP']/$playerinfo['APMAX'])*100);
    	$playerinfo['RT'] = $player->getPlayerPoint('actionPoint')->getRemainTime();
    	$gold=strval($player->gold);
		$len2=strlen($gold);
		$len1=9-$len2;
		$num1='';
		for ($i=1;$i<=$len1;$i++) {
			$num1=$num1.'0';
			if ($i%3==0){
				$num1=$num1.',';
			}
		}
		$num2='';
		for ($i=1;$i<=$len2;$i++) {
			$num2=$gold{$len2-$i}.$num2;
			if ($i%3==0 && $i!=$len2) {
				$num2=','.$num2;
			}
		}
		$playerinfo['num1']=$num1;
		$playerinfo['num2']=$num2;
		return $playerinfo;
	}
}
?>
