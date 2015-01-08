<?php

class MailModel extends Model {

    private $playerId;
    private $player;

    public function __construct($playerId) {
        $this->playerId = $playerId;
        $this->player = Yii::app()->objectLoader->load('Player', $this->playerId);
    }

    /*
     * 获取玩家所有邮件
     */

    public function getPlayerAllMail($flag, $category = 0, $mailId = 0, $nowViewNum = -1, $moreViewNum = MAIL_SHOWMORENUM) {
        $params = array();
        $dataArray = array();
        $param = " AND playerId = " . $this->playerId;
        $param2 = "AND action = 0";
        array_push($params, $param);
        array_push($params, $param2);

        if ($category != 0) {
            $param3 = "AND informType = " . $category;
            array_push($params, $param3);
        }

        if ($mailId != 0) {
            $param4 = "AND mailId = " . $mailId;
            array_push($params, $param4);
        }

        $param5 = 'ORDER BY createTime DESC';
        array_push($params, $param5);

        if ($nowViewNum != -1) {
            $param6 = "LIMIT " . $nowViewNum . ',' . $moreViewNum;
            array_push($params, $param6);
        }

        $dataArray = Mail::multiLoad($params, $flag);
        $dataArray = $this->addField($dataArray);
        return $dataArray;
    }

    public function addField($allMails) {

        foreach ($allMails as $mailId => $mail) {
            if (isset($mail->sentThings) && !empty($mail->sentThings)) {
                $array = unserialize($mail->sentThings);
                $allMails[$mailId]->goodsId = isset($array['goodsId']['goodsId']) ? $array['goodsId']['goodsId'] : MAIL_DEFAULTVALUE;
                $allMails[$mailId]->goodsNum = isset($array['goodsId']['goodsNum']) ? $array['goodsId']['goodsNum'] : MAIL_DEFAULTVALUE;
                $allMails[$mailId]->seedId = isset($array['seedId']['seedId']) ? $array['seedId']['seedId'] : MAIL_DEFAULTVALUE;
                $allMails[$mailId]->seedNum = isset($array['seedId']['seedNum']) ? $array['seedId']['seedNum'] : MAIL_DEFAULTVALUE;
                $allMails[$mailId]->sentGold = isset($array['sentGold']['sentGold']) ? $array['sentGold']['sentGold'] : MAIL_DEFAULTVALUE;
                $allMails[$mailId]->sentGoldNum = isset($array['sentGold']['sendGoldNum']) ? $array['sentGold']['sendGoldNum'] : MAIL_DEFAULTVALUE;
                $allMails[$mailId]->sentMoney = isset($array['sentMoney']['sentMoney']) ? $array['sentMoney']['sentMoney'] : MAIL_DEFAULTVALUE;
                $allMails[$mailId]->sentMoneyNum = isset($array['sentMoney']['sentMoneyNum']) ? $array['sentMoney']['sentMoneyNum'] : MAIL_DEFAULTVALUE;
            } else {
                $allMails[$mailId]->goodsId = MAIL_DEFAULTVALUE;
                $allMails[$mailId]->goodsNum = MAIL_DEFAULTVALUE;
                $allMails[$mailId]->seedId = MAIL_DEFAULTVALUE;
                $allMails[$mailId]->seedNum = MAIL_DEFAULTVALUE;
                $allMails[$mailId]->sentGold = MAIL_DEFAULTVALUE;
                $allMails[$mailId]->sentGoldNum = MAIL_DEFAULTVALUE;
                $allMails[$mailId]->sentMoney = MAIL_DEFAULTVALUE;
                $allMails[$mailId]->sentMoneyNum = MAIL_DEFAULTVALUE;
            }
        }

        return $allMails;
    }

    public function getPlayerAllNotice($flag, $noticeId = 0, $nowViewNum = -1, $moreViewNum = MAIL_SHOWMORENUM) {
        $params = array();
        if ($noticeId != 0) {
            $param1 = "AND noticeId = " . $noticeId;
            array_push($params, $param1);
        }
        $param2 = 'ORDER BY createTime DESC';
        array_push($params, $param2);
        if ($nowViewNum != -1) {
            $param3 = "LIMIT " . $nowViewNum . ',' . $moreViewNum;
            array_push($params, $param3);
        }
        $dataArray = MailNotice::multiLoad($params, $flag);
        return $dataArray;
    }

    public function getPlayerAllMailObject() {
        $params = array();
        $dataArray = array();
        $param = "playerId = " . $this->playerId;
        $param2 = "action = 0";
        array_push($params, $param);
        array_push($params, $param2);
        $dataArray = Mail::multiLoad($params, false);
        foreach ($dataArray as $mail) {
            if ($mail->keepDays != 0 && $mail->keepDays <= time()) {
                $mail->action = 1;
                $mail->saveAttributes(array('action'));
            }
        }
        return $dataArray;
    }

    public function DelKeepDays() {
        $playerAllMail = $this->getPlayerAllMail(false);
        foreach ($playerAllMail as $mail) {
            if ($mail->keepDays != 0 && $mail->keepDays <= time()) {
                $mail->action = 1;
                $mail->saveAttributes(array('action'));
                $this->delMail($mail->mailId);
            }
        }
    }

    public function DelkeepDay($mailId) {
        $mail = Yii::app()->objectLoader->load('Mail', $mailId);
        $mail->action = 1;
        $mail->saveAttributes(array('action'));
        $this->delMail($mail->mailId);
    }

    /*
     * 系统邮件发送
     */

    public static function givePresent($mailInfo, $playerList = array(), $isObject = false) {
        if ($mailInfo['informType'] == MAIL_PLAYERMAIL) {
            $mailInfo['keepDays'] = time() + 86400 * MAIL_MAXKEEPDAYS;

            if (isset($mailInfo['goodsId']) && $mailInfo['goodsId'] != 0) {
                $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $mailInfo['goodsId']);
                $itemObject = $itemMeta->itemObject;
                if (isset($itemObject->level) && $itemObject->level != '') {
                    $levelTime = 3600 * ($itemObject->level * $itemObject->level / 4);
                    $mailInfo['getDays'] = time() + $levelTime;
                }
            }
            if (isset($mailInfo['seedId']) && $mailInfo['seedId'] != 0) {
                $seed = Yii::app()->objectLoader->load('Seed', $mailInfo['seedId']);
                $seedLevel = $seed->getMyLevel();
                $mailInfo['getDays'] = time() + (int) (3600 * ($seedLevel / 4));
            }
            if (isset($mailInfo['sentGold']) && $mailInfo['sentGold'] != 0) {
                //$mailInfo['getDays'] = 公式3;
            }
        }

        MailModel::sendMails($mailInfo, $playerList, $isObject);

        //成就检查
        $achieveEvent = new AchievementEvent($mailInfo['fromId'], ACHIEVEEVENT_PRESENT);
        $achieveEvent->onAchieveComplete();
    }

    public static function sendMails($mailInfo, $playerList = array(), $nowPlayerId = '') {
        $fromFields = array('goodsId' => 'goodsNum', 'seedId' => 'seedNum', 'sentGold' => 'sentGoldNum', 'sentMoney' => 'sentMoneyNum',);
        $toField = 'sentThings';
        if (!isset($mailInfo['fromId'])) {
            throw new CException(Yii::t('Mail', 'you fromId is null'));
        }
        if ($mailInfo['informType'] == MAIL_NOTICEMAIL) {
            $title = filter_var($mailInfo['title'], FILTER_SANITIZE_STRING);
            $content = filter_var($mailInfo['notice'], FILTER_SANITIZE_STRING);
            MailNotice::saveNotice($title, $content);
            return true;
        }
        foreach ($playerList as $playerId) {
            $mailInfo['playerId'] = $playerId;
            $row = Mail::mailCount($mailInfo['playerId']);
            if ($row[0] >= MAIL_MAXMAIL && $mailInfo['informType'] == MAIL_PLAYERMAIL) {
                $rowQueue = MailQueue::queueCount($mailInfo['playerId']);
                if ($rowQueue[0] >= MAIL_MAXQUEUE) {
                    if ($playerId == $nowPlayerId) {
                        throw new SException(Yii::t('Mail', 'you mail queue count is max'));
                    } else {
                        $player = Yii::app()->objectLoader->load('Player', $playerId);
                        throw new SException($player->playerName . Yii::t('Mail', 'you friend mail queue count is max'));
                    }
                }
                $queueInfo = array(
                    'playerId' => $mailInfo['playerId'],
                    'getDays' => isset($mailInfo['getDays']) ? self::changeTime($mailInfo['getDays']) : MAIL_DEFAULTVALUE,
                    'keepDays' => isset($mailInfo['keepDays']) ? self::changeTime($mailInfo['keepDays']) : MAIL_DEFAULTVALUE,
                    'informType' => $mailInfo['informType'],
                    'fromId' => isset($mailInfo['fromId']) ? $mailInfo['fromId'] : MAIL_DEFAULTVALUE,
                    'goodsId' => isset($mailInfo['goodsId']) ? $mailInfo['goodsId'] : MAIL_DEFAULTVALUE,
                    'seedId' => isset($mailInfo['seedId']) ? $mailInfo['seedId'] : MAIL_DEFAULTVALUE,
                    'sentGold' => isset($mailInfo['sentGold']) ? $mailInfo['sentGold'] : MAIL_DEFAULTVALUE,
                    'sentMoney' => isset($mailInfo['sentMoney']) ? $mailInfo['sentMoney'] : MAIL_DEFAULTVALUE,
                    'mailTitle' => isset($mailInfo['mailTitle']) ? $mailInfo['mailTitle'] : '',
                    'content' => isset($mailInfo['content']) ? $mailInfo['content'] : '',
                );
                $queueInfo = self::concordance($queueInfo, $fromFields, $toField);
                MailQueueModel::moveInQueue($queueInfo);
            } else {
                $ignore = array('title', 'notice', 'noticeId');
                $newMailInfo = $mailInfo;
                $newMailInfo = self::ignoreArray($newMailInfo, $ignore);
                $newMailInfo = self::concordance($newMailInfo, $fromFields, $toField);
                $sequence = Mail::create($newMailInfo);
                GlobalMessage::addMailGift($sequence);

                $mailModel = Yii::app()->objectLoader->load('MailModel', $playerId);
                $mailModel->updateStateInfo($mailInfo['informType'], 1, isset($mailInfo['keepDays']) ? $mailInfo['keepDays'] : 0);
                GlobalState::set($playerId, 'HAVE_NEWMAIL', 1);
            }
        }
    }

    public static function concordance($array, $fromFields, $toField) {
        $newArray = array();

        foreach ($fromFields as $arr => $num) {
            if (isset($array[$arr]) && $array[$arr] != MAIL_DEFAULTVALUE) {
                $newArray[$arr][$arr] = $array[$arr];
                if (!isset($array[$num]) || empty($array[$num])) {
                    $newArray[$arr][$num] = 1;
                } else {
                    $newArray[$arr][$num] = $array[$num];
                }
            }
            if (isset($array[$arr])) {
                unset($array[$arr]);
            }
            if (isset($array[$num])) {
                unset($array[$num]);
            }
        }

        $array[$toField] = serialize($newArray);

        return $array;
    }

    public static function changeTime($time) {
        if ($time == 0) {
            return 0;
        } else {
            return $time - time();
        }
    }

    public static function ignoreArray($array, $ignore) {
        $temp = array();
        foreach ($array as $key => $arr) {
            if (!in_array($key, $ignore)) {
                $temp[$key] = $array[$key];
            }
        }
        return $temp;
    }

    /*
     * 用户读取邮件
     */

    public function readMail($mailId) {
        $mail = Yii::app()->objectLoader->load('Mail', $mailId);
        //check point
        $mail->checkOwner($this->playerId);
        $mail->isRead = 1;
        $mail->saveAttributes(array('isRead'));
    }

    /*
     * 用户领取礼品
     */

    public function getPresent($mailId) {
        $mail = Yii::app()->objectLoader->load('Mail', $mailId);
        $mail->checkIsGet();

        $this->entrustMailThings($mail);
        //check point
        $mail->checkOwner($this->playerId);

        //MAIL_DEFAULTVALUE IS 0
        if ($mail->goodsId != MAIL_DEFAULTVALUE) {
            $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $mail->goodsId);
            $item = Yii::app()->objectLoader->load('Item', $this->playerId);
            $item->addItem($itemMeta, 'getPresent', $mail->goodsNum);
        } else if ($mail->sentGold != MAIL_DEFAULTVALUE) {
            $this->player->addGold($mail->sentGold);
        } else if ($mail->sentMoney != MAIL_DEFAULTVALUE) {
            $playerMoney = Yii::app()->objectLoader->load('PlayerMoney', $this->playerId);
            $playerMoney->send($mail->sentMoney, 'Add Money From Mail:MailId' . $mail->mailId);
        }
        $this->delMail($mailId);
    }

    public function entrustMailThings($mail) {
        if (isset($mail->sentThings)) {
            $array = unserialize($mail->sentThings);
            $mail->goodsId = isset($array['goodsId']['goodsId']) ? $array['goodsId']['goodsId'] : MAIL_DEFAULTVALUE;
            $mail->goodsNum = isset($array['goodsId']['goodsNum']) ? $array['goodsId']['goodsNum'] : MAIL_DEFAULTVALUE;
            $mail->seedId = isset($array['seedId']['seedId']) ? $array['seedId']['seedId'] : MAIL_DEFAULTVALUE;
            $mail->seedNum = isset($array['seedId']['seedNum']) ? $array['seedId']['seedNum'] : MAIL_DEFAULTVALUE;
            $mail->sentGold = isset($array['sentGold']['sentGold']) ? $array['sentGold']['sentGold'] : MAIL_DEFAULTVALUE;
            $mail->sentGoldNum = isset($array['sentGold']['sendGoldNum']) ? $array['sentGold']['sendGoldNum'] : MAIL_DEFAULTVALUE;
            $mail->sentMoney = isset($array['sentMoney']['sentMoney']) ? $array['sentMoney']['sentMoney'] : MAIL_DEFAULTVALUE;
            $mail->sentMoneyNum = isset($array['sentMoney']['sentMoneyNum']) ? $array['sentMoney']['sentMoneyNum'] : MAIL_DEFAULTVALUE;
        }
    }

    /*
     * 使用邮票缩短礼品领取等待时间
     */

    public function useStamp($mailId, $stampId) {
        $mail = Yii::app()->objectLoader->load('Mail', $mailId);
        $getDays = $mail->getDays;
        //check point
        $mail->checkOwner($this->playerId);
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $stampId);
        $item = Yii::app()->objectLoader->load('Item', $this->playerId);
        $item->useItem($itemMeta, 'Use Stamp');
        $itemObject = $itemMeta->itemObject;
        if (isset($itemObject->effect['reduceTime'])) {
            $reduce = $itemObject->effect['reduceTime'] * 3600;
        } else {
            throw new CException(Yii::t('Item', 'this is not a stamp'));
        }
        //MAIL_DEFAULTVALUE IS 0
        if ($getDays < $reduce || $reduce == MAIL_DEFAULTVALUE) {
            $newDays = time();

            //快速投递成就检查
            $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_STAMP);
            $achieveEvent->onAchieveComplete();
        } else {
            $newDays = $getDays - $reduce;
        }
        $mail->getDays = $newDays;
        $mail->saveAttributes(array('getDays'));
        $mailId = $mail->mailId;
    }

    /*
     * 种子从邮箱移动到花园
     */

    public function SeedsToGarden($mailId, $seedId, $gardenSign) {

        $mail = Yii::app()->objectLoader->load('Mail', $mailId);
        $mail->checkIsGet();

        $seed = Yii::app()->objectLoader->load('Seed', $seedId);
        $gardenModel = Yii::app()->objectLoader->load('GardenModel', $this->playerId);
        //check point
        $seed->checkOwner($this->playerId);
        $mail->checkOwner($this->playerId);
        $gardenModel->addSeedToGarden($seedId, $gardenSign);
        $this->delMail($mailId);
    }

    /*
     * 获得状态缓存信息
     */

    public function getStateFromCache($key) {
        /**  自定义一个缓存的KEY值 * */
//        $key = 'state_NoticeStartTime';
        /** 获得缓存值 * */
        $cacheData = Yii::app()->cache->get($key);
        //判断缓存是否为空
//        if (empty($cacheData)) {
        $row = MailNotice::getNoticeStartTime();
        /** 设置缓存 * */
        //获得结果集，判断结果集是否为空
        if (empty($row)) {
            Yii::app()->cache->set($key, array());
        } else {
            if ($cacheData != $row)
                Yii::app()->cache->set($key, $row);
        }
        $cacheData = Yii::app()->cache->get($key);
//        }
        /** 返回数据 * */
        return $cacheData;
    }

    //清空缓存
    public function setStateFromCache($key, $value) {
        /**  自定义一个缓存的KEY值 * */
//        $key = 'state_NoticeStartTime';
        /** 获得缓存值 * */
        $cacheData = Yii::app()->cache->get($key);
        if (!empty($cacheData)) {
            /** 设置清空缓存 * */
            Yii::app()->cache->set($key, '');
        }
    }

    /*
     * 写入获取playerState中的值
     */

    public function getStateFromPlayer($key) {
        $status = $this->player->getStatus($key);
        return $status;
    }

    public function setStateFromPlayer($key, $value) {
        $this->player->setStatus($key, $value);
    }

    public function getAllUnReadMailFlag() {
        $count = 0;
        $array = $this->getStateInfo('newMailNum');

        $mailNoticeCount = MailNotice::mailNoticeCount();
        if ($mailNoticeCount[0] == 0) {
            $array[3]['flag'] = 0;
        } else {
            if (isset($array[3]['time'])) {
                $statusData = $array[3]['time'];
                $cacheData = $this->getStateFromCache('noticeStartTime');
                $cacheTemp = 0;
                foreach ($cacheData as $index => $arr) {
                    if ($arr < time() && $arr != false && $cacheTemp < $arr) {
                        $cacheTemp = $arr;
                    }
                }
                $row = MailNotice::getNoticeStartTime();
                if ($cacheTemp > $statusData && !empty($row)) {
                    $array[3]['flag'] = 1;
                } else {
                    $array[3]['flag'] = 0;
                }
            }
        }
//        $this->setStateInfo('newMailNum', $array);
        foreach ($array as $category => $arr) {
            if (isset($array[$category])) {
                if ($array[$category]['flag'] == 1) {
                    if ($category == 2 && $array[$category]['time'] != 0 && $array[$category]['time'] < time()) {
                        continue;
                    }
                    $count++;
                }
            }
        }
        return $count > 0 ? true : false;
    }

    public function updateStateInfo($category, $flag, $time) {
        //更新数据库中的playerStatue
        $array = $this->getStateInfo('newMailNum');
        $array[$category]['flag'] = $flag;
        $array[$category]['time'] = $time;
        $this->setStateInfo('newMailNum', $array);
    }

    public function clearNoticeCache($newTime) {
        $noticeStartTime = Yii::app()->cache->get('noticeStartTime');
        foreach ($noticeStartTime as $index => $arr) {
            if ($noticeStartTime[$index] < $newTime) {
                unset($noticeStartTime[$index]);
            }
        }
        Yii::app()->cache->set('noticeStartTime', $noticeStartTime);
    }

    public function readStateInfo($category) {
        $mailNoticeCount = MailNotice::mailNoticeCount();
        if ($category == 3 && $mailNoticeCount[0] == 0) {
            return false;
        }

        $array = $this->getStateInfo('newMailNum');
        if (isset($array[3]['time'])) {
            $statusData = $array[3]['time'];
            $cacheData = $this->getStateFromCache('noticeStartTime');
            $cacheTemp = 0;
            foreach ($cacheData as $index => $arr) {
                if ($arr < time() && $arr != false && $cacheTemp < $arr) {
                    $cacheTemp = $arr;
                }
            }
            $row = MailNotice::getNoticeStartTime();
            if ($cacheTemp > $statusData && !empty($row)) {
                $array[3]['flag'] = 1;
            } else {
                $array[3]['flag'] = 0;
            }
        }

//        $this->setStateInfo('newMailNum', $array);
        if (isset($array[$category])) {
            if ($array[$category]['flag'] == 1) {
                if ($category == 2 && $array[$category]['time'] != 0 && $array[$category]['time'] < time()) {
                    return false;
                }
                return true;
            }
        }
        return false;
    }

    public function getStateInfo($key) {
        //反序列化获得playerStatue到数组中
        $stateInfo = unserialize($this->getStateFromPlayer($key));
        //如果为空赋予初值
        if (empty($stateInfo)) {
            $stateInfo = array();
            $stateInfo[1]['flag'] = 0;
            $stateInfo[1]['time'] = 0;
            $stateInfo[2]['flag'] = 0;
            $stateInfo[2]['time'] = 0;
            $stateInfo[3]['flag'] = 0;
            $stateInfo[3]['time'] = 0;
        }
        return $stateInfo;
    }

    public function setStateInfo($key, $value) {
        //序列化设置playerStatue
        $serializeValue = serialize($value);
        $this->setStateFromPlayer($key, $serializeValue);
    }

    /*
     * 更新邮件的信息
     */

    public function updateInfo($dataArray, $mailId, $updateList) {
        $updateAttributes = array();
        /** 处理需要保存的字段 * */
        $mail = Yii::app()->objectLoader->load('Mail', $mailId);
        foreach ($updateList as $key) {
            if (isset($dataArray[$key])) {
                /** 字段赋值 * */
                $mail->$key = $dataArray[$key];
                /** 放入保存列表 * */
                $updateAttributes[] = $key;
            }
        }
        /** 保存属性值 * */
        $mail->saveAttributes($updateAttributes);
    }

    /*
     * 获得邮件的信息
     */

    public function getMailInfo($mailId) {
        $data = array();
        $mail = Yii::app()->objectLoader->load('Mail', $mailId);
        //check point
        $mail->checkOwner($this->playerId);
        foreach ($mail->attributeNames() as $key) {
            $data[$key] = $mail->$key;
        }
        return $data;
    }

    public function delMail($mailId) {
        $mail = Yii::app()->objectLoader->load('Mail', $mailId);
        //check point
        $mail->checkOwner($this->playerId);
        //标记队列已经移出
        Mail::delMail($mailId);
        $row = Mail::mailCount($this->playerId);
        if ($mail->informType == MAIL_PLAYERMAIL) {
            $mailQueueModel = Yii::app()->objectLoader->load('MailQueueModel', $this->playerId);
            if ($row[0] < MAIL_MAXMAIL) {
                $mailQueueModel->moveOutQueue();
            }
        }
    }

    public static function mailToHistory() {
        Mail::mailToHistory();
        Mail::delOldMail();
    }

    public static function translateMapping($title, $string) {
        $strripos = strripos($string, '%');

        if ($strripos == false) {
            return $string;
        }

        $contentList = explode('$', $string);
        if ($contentList[0] == '[%missionMail%]') {
            if (isset($contentList[1])) {
                return '「' . Yii::t('Mission', $contentList[1]) . '」' . Yii::t('Mail', $contentList[0]);
            }
            return Yii::t('Mail', $contentList[0]);
        }
        if ($contentList[0] == '[%achievementMail%]') {
            if (isset($contentList[1])) {
                return '「' . Yii::t('Achievement', $contentList[1]) . '」' . Yii::t('Mail', $contentList[0]);
            }
            return Yii::t('Mail', $contentList[0]);
        }

        $contentList = explode('_', $string);
        if ($contentList[0] == '[%recommendFromFirst%]' || $contentList[0] == '[%recommendToFirst%]' || $contentList[0] == '[%recommendFromSecond%]' || $contentList[0] == '[%recommendToSecond%]') {
            $contentStr = Yii::t('Mail', $contentList[0]);
            for ($i = 1; $i < sizeof($contentList); $i++) {
                $contentStr = str_replace('[x' . $i . ']', $contentList[$i], $contentStr);
            }
            return $contentStr;
        }

        if ($contentList[0] == '[%VisualMailContent%]') {
            $contentStr = Yii::t('Mail', $contentList[0]);
            $contentStr = str_replace('[x]', Yii::t('VisualFriend', $contentList[1]), $contentStr);
            return $contentStr;
        }

        if ($contentList[0] == '[%appDriverMail%]') {
            $contentStr = $contentList[1] . Yii::t('Reward', $contentList[0]);
            return $contentStr;
        }

        return Yii::t('Mail', $string);
    }

    public static function substr_cut($sourcestr, $cutlength = 81) {
        $returnstr = '';
        $i = 0;
        $n = 0;
        $str_length = mb_strlen($sourcestr); //字符串的字节数 
        while (($n < $cutlength) and ($i <= $str_length)) {
            $temp_str = mb_substr($sourcestr, $i, 1);
            $ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码 
            if ($ascnum >= 224) {    //如果ASCII位高与224，
                $returnstr = $returnstr . mb_substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符         
                $i = $i + 3;            //实际Byte计为3
                $n++;            //字串长度计1
            } elseif ($ascnum >= 192) { //如果ASCII位高与192，
                $returnstr = $returnstr . mb_substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符 
                $i = $i + 2;            //实际Byte计为2
                $n++;            //字串长度计1
            } elseif ($ascnum >= 65 && $ascnum <= 123) { //如果是大写字母，
                $returnstr = $returnstr . mb_substr($sourcestr, $i, 1);
                $i = $i + 1;            //实际的Byte数仍计1个
                $n++;            //但考虑整体美观，大写字母计成一个高位字符
            } elseif ($ascnum == 0) {
                break;
            } else {                //其他情况下，包括小写字母和半角标点符号，
                $returnstr = $returnstr . mb_substr($sourcestr, $i, 1);
                $i = $i + 1;            //实际的Byte数计1个
                $n = $n + 0.5;        //小写字母和半角标点等与半个高位字符宽...
            }
        }
        if ($str_length > $cutlength) {
            if (mb_strlen($returnstr) < mb_strlen($sourcestr)) {
                $returnstr = $returnstr . "..."; //超过长度时在尾处加上省略号
            }
        }
        return $returnstr;
    }

}

?>
