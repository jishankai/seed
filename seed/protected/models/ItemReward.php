<?php

class ItemReward extends Reward {
    public function __construct($id, $num=1, $from, $title) {
        parent::__construct($id, $num, $from, $title);
    }

    public function reward($playerId) {
        $mailInfo = array();

        $mailInfo['fromId'] = MAIL_DEFAULTFROMID;
        $mailInfo['informType'] = MAIL_SYSTEMMAIL;
        $mailInfo['goodsId'] = $this->getName();
        $mailInfo['goodsNum'] = $this->getValue();
        $mailInfo['content'] = $this->getFrom().'$'.$this->getTitle();
        $mailInfo['mailTitle'] = $this->getTitle();

        MailModel::sendMails($mailInfo, array($playerId));
    }

}

