<?php
/**
 * PlayerRegister
 **/
class PlayerRegister extends CModel
{
    public $userId;
    public $inviterId;
    public $playerName;

    public function attributeNames()
    {
        return array('playerName', 'userId', 'inviterId');
    }

	public function rules()
	{
		return array(
            array('playerName', 'blank', 'message'=>Yii::t('Player', 'the playerName can not be blank')),
            array('playerName', 'width', 'min'=>0, 'max'=>12, 'encoding'=>'utf8'),
            array('playerName', 'charset', 'message'=>Yii::t('Player', 'the playerName includes special chars')),
            array('playerName', 'exist', 'exist'=>false, 'attributeName'=>'playerName', 'message'=>Yii::t('Player', 'the playerName has been registered')),            
            array('inviterId', 'length', 'max'=>4),
		    array('inviterId', 'inviterCharset', 'message'=>Yii::t('Player', 'the inviterId should include numberic and letters only')),
            array('inviterId', 'exist', 'exist'=>true, 'attributeName'=>'inviteId', 'message'=>Yii::t('Player', 'the inviterId does not exist')),
            array('userId', 'exist', 'exist'=>false, 'attributeName'=>'userId', 'message'=>Yii::t('Player', 'the user has registered')),
		);
	}

    public function attributeLabels()
    {
        return array(
            'inviterId' => '招待ID',
            'playerName' => 'ニックネーム',
        );
    } 

    public function blank($attribute, $params)
    {
        if ($this->$attribute == '') {
            $this->addError($attribute, $params['message']);
        }
    }
    
    public function width($attribute, $params)
    {
        $string = $this->$attribute;
        if($string == ""){
            return;
        }
        $minWidth = $params['min'];
        $maxWidth = $params['max'];
        if(isset($params['encoding'])){
            $encoding = $params['encoding'];
        }else{
            $encoding = 'utf8';
        }

        $width = mb_strwidth($string, $encoding);
        if($minWidth !== null && $width < $minWidth){
            $message = Yii::t('Player', '{attribute} is too short', array('{attribute}'=>$this->getAttributeLabel($attribute)));
            $this->addError($attribute, $message);
        }
        if($maxWidth !== null && $width > $maxWidth){
            $message = Yii::t('Player', '{attribute} is too long', array('{attribute}'=>$this->getAttributeLabel($attribute)));
            $this->addError($attribute, $message);
        }
    }
       
	public function exist($attribute, $params)
    {
        if ($this->$attribute != '') {
            $attributeName = $params['attributeName'];
            $command = Yii::app()->db->createCommand();
            $command->setText("SELECT playerId FROM player WHERE $attributeName = :attribute");
            $command->bindValue(':attribute', $this->$attribute);
            $playerId = $command->queryScalar();
            $exist = $params['exist'];
            $valid = $exist ? ($playerId===false ? false : true) : ($playerId===false ? true : false);
            if ($valid === false) {
                $this->addError($attribute, $params['message']);
            }
        }

    }

    public function charset($attribute, $params)
    {
        $string = $this->$attribute;
        if ($string == '') {
            return ;
        }

        //http://www.php.net/manual/en/function.preg-match.php#94424
        //kanji: \x{4E00}-\x{9FBF}
        //hiraga: \x{3040}-\x{309F}
        //fullwidth katakana: \x{30A0}-\x{30FF}
        //halfwidth katakana: \x{FF65}-\x{FF9D}
        //fullwidth alphabeta: \x{FF21}-\x{FF3A}, \x{FF41}-\x{FF5A}
        //fullwidth numbric: \x{FF11}-\x{FF19}
        //halfwidth katakana voiced sound mark and semi-vioced sound mark: \x{FF9E}-\x{FF9F}
        $pattern = '/^[a-zA-Z0-9\x{30A0}-\x{30FF}\x{3040}-\x{309F}\x{4E00}-\x{9FBF}]+$/u';
        if (preg_match($pattern, $string)) {
            return ;
        }else {
            $this->addError($attribute, $params['message']);
        }
    }

    public function inviterCharset($attribute, $params) {
        $string = $this->$attribute;
        if ($string == '') {
            return ;
        } 

        $pattern = '/^[a-zA-Z0-9]+$/u';
        if (preg_match($pattern, $string)) {
            return ;
        }else {
            $this->addError($attribute, $params['message']);
        }
    }
	
	public function createInviteId() {
		$n = 4;
		$command = Yii::app()->db->createCommand("SELECT inviteId FROM player");
		$inviteIds = $command->queryColumn();
		$characters = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x','y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',);
		$j = 0;
		do {
			$inviteId = '';
			for ($i = 0 ; $i < $n ; $i++) {
				$rand = rand(1,62);
				$inviteId .= $characters[$rand-1];
			}
			if (!in_array($inviteId,$inviteIds)) {
				return $inviteId;
			}
			$j++;	
		}while($j<1000);
		throw new SException(Yii::t('PlayerRegister', 'The server is busy. Please submit in a few seconds.'));
	}

    public function register()
    {
		if ($this->inviterId == '') {
		    $inviterId = null;
		} else {
			$command = Yii::app()->db->createCommand('SELECT playerId FROM player WHERE inviteId = :inviteId');
		    $command->bindParam(':inviteId', $this->inviterId);
			$inviterId = $command->queryScalar();
		}
        $createInfo = array(
            'userId' => $this->userId,
            'playerName' => $this->playerName,
            'inviterId' => $inviterId,
			'inviteId' => $this->createInviteId(),
        );

        $player = Player::create($createInfo);
        $player->inviteEvent($player->playerId, $this->inviterId);
		try {
			if (!empty($inviterId)) {
				FriendModel::createFriendRequest($player->playerId, $inviterId);
			}
		}catch (SException $ex) {
        }
        return $player;
    }
}
?>
