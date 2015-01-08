<?php

class SnsModel extends Model {

    private $playerId;
    private $player;

    public function __construct($playerId) {
        $this->playerId = $playerId;
        $this->player = Yii::app()->objectLoader->load('Player', $this->playerId);
    }

    public function getSnsContent() {
        $status = $this->player->getStatus('snsContent');
        return $status;
    }

    public function setSnsContent($value) {
        $this->player->setStatus('snsContent', $value);
    }

    public function getStateInfo() {
        $stateInfo = unserialize($this->getSnsContent());
        if (empty($stateInfo)) {
            $stateInfo = array();
            $stateInfo['isTwitterShare'] = 0;
            $stateInfo['isFacebookShare'] = 0;
            $stateInfo['shareMessage'] = '';
            $stateInfo['shareImageName'] = '';
        }
        return $stateInfo;
    }

    public function setStateInfo($value) {
        $serializeValue = serialize($value);
        $this->setSnsContent($serializeValue);
    }

    //检查Twitter绑定
    public static function checkTwitterBing() {
        require_once dirname(__FILE__) . Yii::app()->params['twitter']['extFile1'];
        require_once dirname(__FILE__) . Yii::app()->params['twitter']['extFile2'];

        $session = Yii::app()->session;
        $session->open();
        $userId = $session['userId'];
        $userModel = new UserModel('UserTwitter');

        $tmhOAuth = new tmhOAuth(array(
                    'consumer_key' => Yii::app()->params['twitter']['consumer_key'],
                    'consumer_secret' => Yii::app()->params['twitter']['consumer_secret'],
                ));
        $tokenArray = $userModel->getTwitterTokenDB($userId);
        if (!empty($tokenArray)) {
            $tmhOAuth->config['user_token'] = $tokenArray['userToken'];
            $tmhOAuth->config['user_secret'] = $tokenArray['userSecret'];
        }
        $code = $tmhOAuth->request('GET', $tmhOAuth->url('1/account/verify_credentials'));
        if ($code != 200) {
            return false;
        } else {
            return true;
        }
    }

    //检查Facebook绑定
    public static function checkFacebookBing() {
        require_once(dirname(__FILE__) . Yii::app()->params['facebook']['extFile']);

        $session = Yii::app()->session;
        $session->open();
        $userId = $session['userId'];
        $userModel = new UserModel('UserTwitter');

        $config = array(
            'appId' => Yii::app()->params['facebook']['appId'],
            'secret' => Yii::app()->params['facebook']['secret'],
            'fileUpload' => true,
        );
        $facebook = new Facebook($config);
        $access_token = trim($userModel->getFaceTokenDB($userId));
        $facebook->setAccessToken($access_token);
        $user_id = $facebook->getUser();
        if ($user_id) {
            return true;
        } else {
            return false;
        }
    }
}

?>
