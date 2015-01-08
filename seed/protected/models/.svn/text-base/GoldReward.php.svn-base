<?php
/**
 * GoldReward
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-02-27
 * @package Seed
 **/
class GoldReward extends Reward
{
    function __construct($value, $from, $title)
    {
        parent::__construct('gold', $value, $from, $title);
    }

    public function reward($playerId)
    {
        $mailInfo = array();

        $mailInfo['fromId'] = MAIL_DEFAULTFROMID;
        $mailInfo['informType'] = MAIL_SYSTEMMAIL;
        $mailInfo['sentGold'] = $this->getValue();
        $mailInfo['content'] = $this->getFrom().'$'.$this->getTitle();
        $mailInfo['mailTitle'] = $this->getTitle();

        MailModel::sendMails($mailInfo, array($playerId));
    }
}
?>
