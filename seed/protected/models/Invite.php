<?php

/**
 * Invite
 * */
class Invite extends CBehavior {

    private $_award;

    public function __construct() {
        $this->_award = Util::loadconfig('award');
    }

    public function searchPlayerIdByInviteId($inviteId) {
        $command = Yii::app()->db->createCommand("SELECT playerid FROM player WHERE inviteid = :inviteId");
        $id = $command->bindParam(':inviteId', $inviteId)->queryScalar();
        if ($id) {
            return $id;
        } else {
            return null;
        }
    }

    public function inviteEvent($playerId, $inviteId) {
        $userList = array();
        if (!empty($inviteId)) {
            $inviterId = $this->searchPlayerIdByInviteId($inviteId);
            if (!empty($inviterId)) {
                $userList['inviter'] = $inviterId;
                $userList['player'] = $playerId;
                $inviter = new Player($inviterId);
                self::addAward($userList, $this->_award[0], 0);

                //成就检查
                $achieveEvent = new AchievementEvent($inviter->playerId, ACHIEVEEVENT_INVITER, array('inviterId' => $inviterId));
                $achieveEvent->onAchieveComplete();
            }
        }
    }

    public function addAward($userList, $award, $id) {
        $ID1 = $userList['inviter'];
        $ID2 = $userList['player'];
        $name = Yii::app()->objectLoader->load('Player', $ID2)->playerName;
        if (!empty($award['seed'])) {
            $mailInfo1 = array();
            $seedModel1 = Yii::app()->objectLoader->load('SeedModel', $ID1);
            $seed1 = $seedModel1->generateSeed(SEED_FROM_SYSTEM);
            $mailInfo1['seedId'] = $seed1->seedId;
            $mailInfo1['fromId'] = MAIL_DEFAULTFROMID;
            $mailInfo1['informType'] = MAIL_SYSTEMMAIL;
            $mailInfo1['content'] = '[%recommendFromSecond%]_' . $name . '_' . $id;
            $user1 = array($ID1);
            MailModel::sendMails($mailInfo1, $user1);
            $mailInfo2 = array();
            $seedModel2 = Yii::app()->objectLoader->load('SeedModel', $ID2);
            $seed2 = $seedModel2->generateSeed(SEED_FROM_SYSTEM);
            $mailInfo2['seedId'] = $seed2->seedId;
            $mailInfo2['fromId'] = MAIL_DEFAULTFROMID;
            $mailInfo2['informType'] = MAIL_SYSTEMMAIL;
            $mailInfo2['content'] = '[%recommendToSecond%]_' . $id;
            $user2 = array($ID2);
            MailModel::sendMails($mailInfo2, $user2);
        } else {
            $mailInfo1 = array();
            $mailInfo2 = array();
            if (!empty($award['money'])) {
                $mailInfo1['sentMoney'] = $award['money'];
                $mailInfo2['sentMoney'] = $award['money'];
            }
            if (!empty($award['goodsId'])) {
                $mailInfo1['goodsId'] = $award['goodsId'];
                $mailInfo2['goodsId'] = $award['goodsId'];
            }
            if (!empty($award['goodsNum'])) {
                $mailInfo1['goodsNum'] = $award['goodsNum'];
                $mailInfo2['goodsNum'] = $award['goodsNum'];
            }
            if (!empty($award['sentGold'])) {
                $mailInfo1['sentGold'] = $award['sentGold'];
                $mailInfo2['sentGold'] = $award['sentGold'];
            }
            $mailInfo1['fromId'] = MAIL_DEFAULTFROMID;
            $mailInfo2['fromId'] = MAIL_DEFAULTFROMID;
            $mailInfo1['informType'] = MAIL_SYSTEMMAIL;
            $mailInfo2['informType'] = MAIL_SYSTEMMAIL;
            if ($id == 0) {
                $mailInfo1['content'] = '[%recommendFromFirst%]_' . $name;
                $mailInfo2['content'] = '[%recommendToFirst%]';
            } else {
                $mailInfo1['content'] = '[%recommendFromSecond%]_' . $name . '_' . $id;
                $mailInfo2['content'] = '[%recommendToSecond%]_' . $id;
            }
            $user1 = array($ID1);
            $user2 = array($ID2);
            MailModel::sendMails($mailInfo1, $user1);
            MailModel::sendMails($mailInfo2, $user2);
        }
    }

    public function inviteAward() {
        $level = $this->getOwner()->level;
        $userList = array();
        if (!empty($this->getOwner()->inviterId)) {
            $userList['inviter'] = $this->getOwner()->inviterId;
            $userList['player'] = $this->getOwner()->playerId;

            switch ($level) {
                case 10 : $this->addAward($userList, $this->_award[10], 10);
                    break;
                case 20 : $this->addAward($userList, $this->_award[20], 20);
                    break;
                case 30 : $this->addAward($userList, $this->_award[30], 30);
                    break;
                case 40 : $this->addAward($userList, $this->_award[40], 40);
                    break;
                case 50 : $this->addAward($userList, $this->_award[50], 50);
                    break;
                case 60 : $this->addAward($userList, $this->_award[60], 60);
                    break;
                case 70 : $this->addAward($userList, $this->_award[70], 70);
                    break;
                case 80 : $this->addAward($userList, $this->_award[80], 80);
                    break;
                case 90 : $this->addAward($userList, $this->_award[90], 90);
                    break;
                case 100 : $this->addAward($userList, $this->_award[100], 100);
                    break;
                default : break;
            }
        }
    }

}

?>
