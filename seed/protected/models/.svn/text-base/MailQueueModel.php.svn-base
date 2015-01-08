<?php

class MailQueueModel extends Model {

    private $player;
    private $playerId;

    public function __construct($playerId) {
        $this->playerId = $playerId;
        $this->player = Yii::app()->objectLoader->load('MailQueue', $this->playerId);
    }

    /*
     * 将数据写入邮件队列
     */

    public static function moveInQueue($queueInfo) {
        return MailQueue::create($queueInfo);
    }

    /*
     * 将数据写出邮件队列
     */

    public function moveOutQueue() {
        $playerId = $this->playerId;
        $array = MailQueue::getMaxQueueByPlayer($playerId);
        if (!empty($array) && isset($array['queueId'])) {
            $queueInfo = array();
            $mailQueue = Yii::app()->objectLoader->load('MailQueue', $array['queueId']);
            foreach ($mailQueue->attributeNames() as $key) {
                $queueInfo[$key] = $mailQueue->$key;
            }
            $mailInfo = array(
                'playerId' => $queueInfo['playerId'],
                'getDays' => $queueInfo['getDays'] + time(),
                'keepDays' => $queueInfo['keepDays'] + time(),
                'informType' => $queueInfo['informType'],
                'fromId' => $queueInfo['fromId'],
                'mailTitle' => $queueInfo['mailTitle'],
                'content' => $queueInfo['content'],
                'sentThings' => $queueInfo['sentThings'],
            );
            //MAIL_DEFAULTVALUE IS 0
            if ($queueInfo['getDays'] == MAIL_DEFAULTVALUE) {
                $mailInfo['getDays'] = MAIL_DEFAULTVALUE;
            }
            if ($queueInfo['keepDays'] == MAIL_DEFAULTVALUE) {
                $mailInfo['keepDays'] = MAIL_DEFAULTVALUE;
            }

            $sequence = Mail::create($mailInfo);

            //标记队列已经移出
            $mailQueue->isMoveOut = 1;
            $mailQueue->saveAttributes(array('isMoveOut'));

            GlobalMessage::addMailGift($sequence);

            $mailModel = Yii::app()->objectLoader->load('MailModel', $this->playerId);
            $mailModel->updateStateInfo($mailInfo['informType'], 1, isset($mailInfo['keepDays']) ? $mailInfo['keepDays'] : 0);
//            $mailModel->updateStateInfo($mailInfo['informType'], 1, $mailInfo['keepDays']);
//            GlobalState::set($this->playerId, 'HAVE_NEWMAIL', 1);
        }
    }

    /*
     * 获得缓存信息
     */

    public function getInfoFromCache() {
        /**  自定义一个缓存的KEY值 * */
        $key = 'mailQueueInfo' . $this->queueId;
        /** 获得缓存值 * */
        $cacheData = $this->cache($key);
        if (empty($cacheData)) {
            $cacheData = $this->getInfo();
            /** 设置缓存 * */
            $this->cache($key, $cacheData);
        }
        /** 返回数据 * */
        return $cacheData;
    }

    /*
     * 更新邮件队列的信息
     */

    public function updateInfo($dataArray, $mailId, $updateList) {
        $updateAttributes = array();
        /** 处理需要保存的字段 * */
        $mailQueue = Yii::app()->objectLoader->load('MailQueue', $mailId);
        foreach ($updateList as $key) {
            if (isset($dataArray[$key])) {
                /** 字段赋值 * */
                $mailQueue->$key = $dataArray[$key];
                /** 放入保存列表 * */
                $updateAttributes[] = $key;
            }
        }
        /** 保存属性值 * */
        $mailQueue->saveAttributes($updateAttributes);
    }

    /*
     * 获得邮件队列信息
     */

    public function getMailQueueInfo($queueId) {
        $data = array();
        $queue = Yii::app()->objectLoader->load('MailQueue', $queueId);
        //check point
        $queue->checkOwner($this->playerId);
        foreach ($queue->attributeNames() as $key) {
            $data[$key] = $queue->$key;
        }
        return $data;
    }

}

?>