<?php

class UserModel extends CComponent {

    private $_userId;
    private $_type;

    public function __construct($type = '', $userId = null) {
        $this->_type = $type;
        $this->_userId = $userId;
    }

    //新建游戏帐号
    public function interactionUID($UID = null) {
        $userId = null;
        $transaction = Yii::app()->db->beginTransaction();
        try {
            if (empty($UID)) {
                throw new SException(Yii::t('View', 'param error'));
            }

            $userId = User::findIdByDevice($UID);
            if (empty($userId)) {
                //调用登录新建立帐号
            }

            //调用登录后实例化userMoney
            //create userMoney

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $this->_userId = $userId;
        $this->resetSession();
        return $this->_userId;
    }

    //新建绑定帐号
    public function interactionAccount($userId = null, $systemId = null, $systemName = null) {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $type = $this->_type;
            if (empty($type) || empty($userId) || empty($systemId)) {
                throw new SException(Yii::t('View', 'param error'));
            }

            //check user exists or not
            $userId_SNS = $type::findIdBySystemId($systemId);

            if (empty($userId_SNS)) {
                $dataCreate = array(
                    'userId' => $userId,
                    'createTime' => time(),
                    'systemId' => $systemId,
                    'systemName' => $systemName,
                );
                $userId_SNS = $type::create($dataCreate);
            } else if ($userId != $userId_SNS) {
                throw new SException(Yii::t('View', $type . ' cannot used two times'));
            } else {
                $dataUpdate = array(
                    'systemName' => $systemName,
                );
                $updateList = array('systemName');
                $this->updateInfo($type, $dataUpdate, $userId_SNS, $updateList);
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $this->_userId = $userId_SNS;
        //$this->resetSession();
        return $this->_userId;
    }

    public function updateInfo($type, $dataArray, $Id, $updateList) {
        $updateAttributes = array();
        /** 处理需要保存的字段 * */
        $pattern = Yii::app()->objectLoader->load($type, $Id);
        foreach ($updateList as $key) {
            if (isset($dataArray[$key])) {
                /** 字段赋值 * */
                $pattern->$key = $dataArray[$key];
                /** 放入保存列表 * */
                $updateAttributes[] = $key;
            }
        }
        /** 保存属性值 * */
        $pattern->saveAttributes($updateAttributes);
    }

    //获取绑定帐号的user
    public function getUser() {
        $userId = $this->_userId;
        $type = $this->_type;
        if (empty($userId) || empty($type)) {
            return null;
        }

        $id = $type::findIdByUserId($userId);
        $user = new $type($id);
        return $user;
    }

    //重置Session
    public function resetSession() {
//        $userId = $this->_userId;
//        if (!isset($userId)) {
//            throw new SException(Yii::t('View', 'param error'));
//        }
//
//        $session = Yii::app()->session;
//        $session->open();
//        $currentUser = new SevenUser($userId);
//        if (isset($currentUser)) {
//            if ($currentUser->sessionId != $session->sessionId) {
//                $session->destroySession($currentUser->sessionId);
//                $currentUser->sessionId = $session->sessionId;
//                $currentUser->saveData('sessionId');
//            }
//        } else {
//            throw new SException(Yii::t('View', 'SeedUser not exist'));
//        }
//        $session['userId'] = $userId;
    }

    //系统已有帐号时候绑定帐号
    public function reinteractionAccount($systemId = null, $systemName = null) {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $userId = $this->_userId;
            $type = $this->_type;
            if (empty($type) || empty($userId) || empty($systemId)) {
                throw new SException(Yii::t('View', 'param error'));
            }

            //check user exists or not
            /* @var $userId_SNS type */
            $userId_SNS = $type::findIdBySystemId($systemId);
            //$id = $type::findIdByUserId($userId_T);
            //$user = new $type($id);
            if (empty($userId_SNS)) {
                $dataCreate = array(
                    'userId' => $userId,
                    'createTime' => time(),
                    'systemId' => $systemId,
                    'systemName' => $systemName,
                );
                $type::create($dataCreate);
            } elseif ($userId_SNS != $userId) {
                throw new SException(Yii::t('View', $type . ' cannot used two times'));
            } else {
                $dataUpdate = array(
                    'systemName' => $systemName,
                );
                $updateList = array('systemName');
                $this->updateInfo($type, $dataUpdate, $userId_SNS, $updateList);
            }

            $transaction->commit();
            return $userId_SNS;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function getFaceTokenDB($userId) {
        return $tokenArray = UserFacebook::findTokenByUserId($userId);
    }

    public function getTwitterTokenDB($userId) {
        return $tokenArray = UserTwitter::findTokenByUserId($userId);
    }

    public function setFaceTokenDB($userId, $accessToken) {
        $id = UserFacebook::findIdByUserId($userId);

        if (empty($id)) {
            $dataCreate = array(
                'userId' => $userId,
                'createTime' => time(),
                'accessToken' => $accessToken,
            );
            $id = UserFacebook::create($dataCreate);
        } else {
            $dataUpdate = array(
                'accessToken' => $accessToken,
            );
            $updateList = array('accessToken');
            $this->updateInfo('UserFacebook', $dataUpdate, $id, $updateList);
        }
    }

    public function setTwitterTokenDB($userId, $userToken, $userSecret) {
        $id = UserTwitter::findIdByUserId($userId);

        if (empty($id)) {
            $dataCreate = array(
                'userId' => $userId,
                'createTime' => time(),
                'userToken' => $userToken,
                'userSecret' => $userSecret,
            );
            $id = UserTwitter::create($dataCreate);
        } else {
            $dataUpdate = array(
                'userToken' => $userToken,
                'userSecret' => $userSecret,
            );
            $updateList = array('userToken','userSecret');
            $this->updateInfo('UserTwitter', $dataUpdate, $id, $updateList);
        }
    }
    
    public function delFaceTokenDB($userId) {
        UserFacebook::delFacebookToken($userId);
    }

    public function delTwitterTokenDB($userId) {
        UserTwitter::delTwitterToken($userId);
    }

}
