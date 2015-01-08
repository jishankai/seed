<?php

class SeedReward extends Reward {
    public function __construct($id, $num=1, $from, $title) {
        parent::__construct($id, $num, $from, $title);
    }

    public function reward($playerId) {
        $mailInfo = array();

        $seedModel = Yii::app()->objectLoader->load('SeedModel', $playerId);
        //万物之初任务送的种子
        if ($this->getName()=="1002:2002:3002") {
            $seed = $seedModel->addSeed(1002, 2002, 3002, 0, SEED_FROM_SYSTEM); 
            $seed->setGrowValue($seed->getMaxGrowValue()-5);
            $seedId = $seed->seedId;
        } else {
            $ids = explode(":", $this->getName());
            if (!empty($ids[1]) && !empty($ids[2])) {
                $seed = $seedModel->addSeed($ids[0], $ids[1], $ids[2], 0, SEED_FROM_SYSTEM); 
                $seed->setGrowValue($seed->getMaxGrowValue());
                $seedId = $seed->seedId;
            } else {
                $seedId = $seedModel->generateSeedByBodyId($ids[0], SEED_GROW_PERIOD_GROWN);
            }
           
        }
        $mailInfo['fromId'] = MAIL_DEFAULTFROMID;
        $mailInfo['informType'] = MAIL_SYSTEMMAIL;
        $mailInfo['seedId'] = $seedId;
        $mailInfo['seedNum'] = $this->getValue();
        $mailInfo['content'] = $this->getFrom().'$'.$this->getTitle();
        $mailInfo['mailTitle'] = $this->getTitle();

        MailModel::sendMails($mailInfo, array($playerId));
    }

}

