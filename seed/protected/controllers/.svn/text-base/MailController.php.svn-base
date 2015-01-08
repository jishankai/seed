<?php

class MailController extends Controller {
    /*
     * 初始化处理方法
     */

    public function init() {
        parent::init();
        //var_dump($this);exit;
    }

    /*
     * 显示邮件列表
     */

    public function actionMailShow() {
        $category = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : 1;
        $nowViewNum = isset($_REQUEST['nowViewNum']) ? intval($_REQUEST['nowViewNum']) : 0;
        $moreViewNum = isset($_REQUEST['moreViewNum']) ? intval($_REQUEST['moreViewNum']) : 0;
        $moreAction = isset($_REQUEST['moreAction']) ? intval($_REQUEST['moreAction']) : 0;
        $isAjaxRefresh = isset($_REQUEST['isAjaxRefresh']) ? intval($_REQUEST['isAjaxRefresh']) : 0;
        $params = $this->mailListRefresh($category, $nowViewNum, $moreViewNum, $moreAction);
        if ($moreViewNum == 0 && $moreAction == 0 && $isAjaxRefresh == 0) {
            $this->display('mailInfoMulti', $params);
        } else if ($moreViewNum == 0 && $moreAction == 0 && $isAjaxRefresh == 1) {
            $data = $this->renderPartial('mailInfoMulti', $params, true);
            $this->display($data);
        } else {
            //var_dump($params);exit();

            $this->display($params);
        }
    }

    public function mailListRefresh($category, $nowViewNum, $moreViewNum, $moreAction = 0) {
        $systemMailReadFlag = 0;
        $playerMailReadFlag = 0;
        $noticeMailReadFlag = 0;
        $this->layout = "//layouts/theme";
        $categories = array(
            1 => '系统礼物箱',
            2 => '礼物箱',
            3 => '公告',
        );
        if (!isset($categories[$category]))
            $category = 1;

        if ($category == 2) {
            Yii::app()->params['inGiftBox'] = true;
        }

        $mailModel = Yii::app()->objectLoader->load('MailModel', $this->playerId);
        $mailModel->DelKeepDays();

        //新手引导        
        $guideModel = Yii::app()->objectLoader->load('GuideModel', $this->playerId);
        $currentGuide = $guideModel->isCurrentGuide(30);
        $accessLevel = GuideModel::getAccessLevel($this->playerId);

        //新手引导控制器
        //$currentGuide = 1;

        if ($category == 1) {
            $mailModel->updateStateInfo(1, 0, 0);
            $systemMailCount = Mail::systemMailCount($this->playerId);
            $littleViewNum = $systemMailCount[0];

            if ($moreAction == 0) {
                $nextViewNum = $moreViewNum + MAIL_SHOWMORENUM;
            } else {
                $nextViewNum = $moreViewNum;
            }

            $playerAllMail = $mailModel->getPlayerAllMail(true, $category, 0, $nowViewNum, $nextViewNum);

            $ishiddenMoreShow = 0;
            if ($nextViewNum >= $systemMailCount[0]) {
                $ishiddenMoreShow = 1;
            }

            $playerMailReadFlag = $mailModel->readStateInfo(2);
            $noticeMailReadFlag = $mailModel->readStateInfo(3);

            $params = array('categories' => $categories,
                'category' => $category,
                'allMails' => $playerAllMail,
                'moreViewNum' => $nextViewNum,
                'ishiddenMoreShow' => $ishiddenMoreShow,
                'littleViewNum' => $littleViewNum,
                'systemMailReadFlag' => false,
                'playerMailReadFlag' => $playerMailReadFlag,
                'noticeMailReadFlag' => $noticeMailReadFlag,
                'welcomeAction' => $currentGuide == 1 ? true : false,
                'accessLevel' => $accessLevel,
            );
        } else if ($category == 2) {
            $mailModel->updateStateInfo(2, 0, 0);
            $mailCount = Mail::mailCount($this->playerId);
            $littleViewNum = $mailCount[0];
            $mailQueueCount = MailQueue::queueCount($this->playerId);
            $playerAllMail = $mailModel->getPlayerAllMail(true, $category, 0, 0, MAIL_MAXMAIL);

            $systemMailReadFlag = $mailModel->readStateInfo(1);
            $noticeMailReadFlag = $mailModel->readStateInfo(3);

            $params = array('categories' => $categories,
                'category' => $category,
                'allMails' => $playerAllMail,
                'mailCount' => $mailCount[0],
                'mailQueueCount' => $mailQueueCount[0],
                'littleViewNum' => $littleViewNum,
                'systemMailReadFlag' => $systemMailReadFlag,
                'playerMailReadFlag' => false,
                'noticeMailReadFlag' => $noticeMailReadFlag,
                'welcomeAction' => $currentGuide == 1 ? true : false,
                'accessLevel' => $accessLevel,
            );
        } else if ($category == 3) {
            $row = MailNotice::getNoticeStartTime();
            if (empty($row)) {
                $noticeStartTime = 0;
            } else {
                $noticeStartTime = $row['startTime'];
            }
            $mailModel->updateStateInfo(3, 0, $noticeStartTime);
            $mailModel->clearNoticeCache($noticeStartTime);
            $mailNoticeCount = MailNotice::mailNoticeCount();
            $littleViewNum = $mailNoticeCount[0];

            if ($moreAction == 0) {
                $nextViewNum = $moreViewNum + MAIL_SHOWMORENUM;
            } else {
                $nextViewNum = $moreViewNum;
            }

            $playerNotice = $mailModel->getPlayerAllNotice(true, 0, $nowViewNum, $nextViewNum);

            $ishiddenMoreShow = 0;
            if ($nextViewNum >= $mailNoticeCount[0]) {
                $ishiddenMoreShow = 1;
            }

            $systemMailReadFlag = $mailModel->readStateInfo(1);
            $playerMailReadFlag = $mailModel->readStateInfo(2);

            $params = array('categories' => $categories,
                'category' => $category,
                'allNotices' => $playerNotice,
                'moreViewNum' => $nextViewNum,
                'ishiddenMoreShow' => $ishiddenMoreShow,
                'littleViewNum' => $littleViewNum,
                'systemMailReadFlag' => $systemMailReadFlag,
                'playerMailReadFlag' => $playerMailReadFlag,
                'noticeMailReadFlag' => false,
                'welcomeAction' => $currentGuide == 1 ? true : false,
                'accessLevel' => $accessLevel,
            );
        }
        //全局消息 移除新邮件提示
        GlobalMessage::removeMailMessage($this->playerId);
        $allUnReadCount = $mailModel->getAllUnReadMailFlag();
        GlobalState::set($this->playerId, 'HAVE_NEWMAIL', $allUnReadCount == true ? 1 : 0);

        if ($moreViewNum == 0 && $moreAction == 0) {
            return $params;
        } else {
            if ($category == 1) {
                $viewFile = 'systemMailList';
            }
            if ($category == 2) {
                $viewFile = 'playerMailList';
            }
            if ($category == 3) {
                $viewFile = 'noticeMailList';
            }
            $data = $this->renderPartial($viewFile, $params, true);
            return $data;
        }
    }

    public function actionMailRefresh() {
        $category = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : 1;
        $mailId = isset($_REQUEST['mailId']) ? intval($_REQUEST['mailId']) : 0;
        $noticeId = isset($_REQUEST['noticeId']) ? intval($_REQUEST['noticeId']) : 0;
        if ($category == 2) {
            Yii::app()->params['inGiftBox'] = true;
        }
        $data = $this->mailRefresh($category, $mailId, $noticeId);
        $this->display($data);
    }

    public function mailRefresh($category, $mailId = 0, $noticeId = 0) {
        $this->layout = "//layouts/theme";
        $categories = array(
            1 => '系统礼物箱',
            2 => '礼物箱',
            3 => '公告',
        );
        if (!isset($categories[$category]))
            $category = 1;
        $mailModel = Yii::app()->objectLoader->load('MailModel', $this->playerId);
        $mailModel->DelKeepDays();
        if ($category == 1) {
            $playerAllMail = $mailModel->getPlayerAllMail(true, 1, $mailId);
            $params = array(
                'categories' => $categories,
                'category' => $category,
                'allMails' => $playerAllMail,
            );
        } else if ($category == 2) {
            //跳过提示
            Yii::app()->params['inGiftBox'] = true;

            $mailCount = Mail::mailCount($this->playerId);
            $mailQueueCount = MailQueue::queueCount($this->playerId);
            $playerAllMail = $mailModel->getPlayerAllMail(true, 2, $mailId);
            $params = array(
                'categories' => $categories,
                'category' => $category,
                'allMails' => $playerAllMail,
                'mailCount' => $mailCount[0],
                'mailQueueCount' => $mailQueueCount[0],
            );
        } else if ($category == 3) {
            $playerNotice = $mailModel->getPlayerAllNotice(true, $noticeId);
            $params = array(
                'categories' => $categories,
                'category' => $category,
                'allNotices' => $playerNotice,
            );
        }
        //全局消息 移除新邮件提示
        GlobalMessage::removeMailMessage($this->playerId);
        if ($mailId != 0 || $noticeId != 0) {
            if ($mailId != 0) {
                $mailModel = Yii::app()->objectLoader->load('MailModel', $this->playerId);
                $mailModel->readMail($_REQUEST['mailId']);
            }
            if ($category == 1) {
                $viewFile = 'systemMailInfo';
            }
            if ($category == 2) {
                $viewFile = 'playerMailInfo';
            }
            if ($category == 3) {
                $viewFile = 'noticeMailInfo';
            }
            $data = $this->renderPartial($viewFile, $params, true);
            return $data;
        }
    }

    /*
     * 获取礼物
     */

    public function actionGetPresent() {
        $nowViewNum = isset($_REQUEST['nowViewNum']) ? intval($_REQUEST['nowViewNum']) : 0;
        $moreViewNum = isset($_REQUEST['moreViewNum']) ? intval($_REQUEST['moreViewNum']) : 0;
        $category = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : 1;
        if (!(isset($_REQUEST['mailId']))) {
            $this->error('mailId is null');
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $mailModel = Yii::app()->objectLoader->load('MailModel', $this->playerId);
            $mailModel->getPresent($_REQUEST['mailId']);
            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        if (Yii::app()->objectLoader->load('Mail', $_REQUEST['mailId'])->informType == 2) {
            Yii::app()->params['inGiftBox'] = true;
        }

        $params = $this->mailListRefresh($category, $nowViewNum, $moreViewNum, 1);

        $this->display($params);
    }

    /*
     * 获取种子
     */

    public function actionGetSeed() {
        $error = 0;
        $nowViewNum = isset($_REQUEST['nowViewNum']) ? intval($_REQUEST['nowViewNum']) : 0;
        $moreViewNum = isset($_REQUEST['moreViewNum']) ? intval($_REQUEST['moreViewNum']) : 0;
        $category = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : 1;
        if (!(isset($_REQUEST['mailId']))) {
            $this->error('mailId is null');
        }
        if (!(isset($_REQUEST['seedId']))) {
            $this->error('seedId is null');
        }
        if (Yii::app()->objectLoader->load('Mail', $_REQUEST['mailId'])->informType == 2) {
            Yii::app()->params['inGiftBox'] = true;
        }

        $transaction = Yii::app()->db->beginTransaction();
        try {
            $gardenSign = 1;
            if (isset($_REQUEST['gardenId'])) {
                $garden = Yii::app()->objectLoader->load('Garden', $_REQUEST['gardenId']);
                $gardenSign = $garden->gardenSign;
            }
            $mailModel = Yii::app()->objectLoader->load('MailModel', $this->playerId);
            $mailModel->SeedsToGarden($_REQUEST['mailId'], $_REQUEST['seedId'], $gardenSign);
            $transaction->commit();
            $params = $this->mailListRefresh($category, $nowViewNum, $moreViewNum, 1, $error);
            $this->display($params);
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

    /*
     * 删除邮件
     */

    public function actionDelMail() {
        $nowViewNum = isset($_REQUEST['nowViewNum']) ? intval($_REQUEST['nowViewNum']) : 0;
        $moreViewNum = isset($_REQUEST['moreViewNum']) ? intval($_REQUEST['moreViewNum']) : 0;
        $category = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : 1;
        if (!(isset($_REQUEST['mailId']))) {
            $this->error('mailId is null');
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $mailModel = Yii::app()->objectLoader->load('MailModel', $this->playerId);
            $mailModel->delMail($_REQUEST['mailId']);
            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        $params = $this->mailListRefresh($category, $nowViewNum, $moreViewNum, 1);

        $this->display($params);
    }

    public function actionDelKeepDays() {
        $nowViewNum = isset($_REQUEST['nowViewNum']) ? intval($_REQUEST['nowViewNum']) : 0;
        $moreViewNum = isset($_REQUEST['moreViewNum']) ? intval($_REQUEST['moreViewNum']) : 0;
        $category = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : 1;
        if (!(isset($_REQUEST['mailId']))) {
            $this->error('mailId is null');
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $mailModel = Yii::app()->objectLoader->load('MailModel', $this->playerId);
            $mailModel->DelkeepDay($_REQUEST['mailId']);
            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        $params = $this->mailListRefresh($category, $nowViewNum, $moreViewNum, 1);

        $this->display($params);
    }

    /*
     * 使用邮票
     */

    public function actionStampUse() {
        if (!(isset($_REQUEST['mailId']))) {
            $this->error('mailId is null');
        } else {
            $mailId = $_REQUEST['mailId'];
        }
        if (!(isset($_REQUEST['stampId']))) {
            $this->error('stampId is null');
        } else {
            $stampId = $_REQUEST['stampId'];
        }
        if (Yii::app()->objectLoader->load('Mail', $mailId)->informType == 2) {
            Yii::app()->params['inGiftBox'] = true;
        }

        $transaction = Yii::app()->db->beginTransaction();
        try {
            $itemModel = Yii::app()->objectLoader->load('ItemModel', $this->playerId);
            $itemModel->itemUse($stampId, $mailId);
            $transaction->commit();
            $getDays = Yii::app()->objectLoader->load('Mail', $mailId)->getDays;
            if ($getDays <= time()) {
                //快速投递成就检查
                $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_STAMP);
                $achieveEvent->onAchieveComplete();
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
        $playerMailInfo = $this->mailRefresh(2, $mailId);
        $getDaysView = $this->getDaysRefresh($mailId);
        $params = array(
            'isOk' => true,
            'getDaysView' => $getDaysView,
            'playerMailInfo' => $playerMailInfo,
        );
        $this->display($params);
    }

    public function getDaysRefresh($mailId) {
        $mail = Yii::app()->objectLoader->load('Mail', $mailId);
        $params = array('mail' => $mail,
        );
        $data = $this->renderPartial('getDaysView', $params, true);
        return $data;
    }

    /* 提示 */

    public function actionStampNotice() {
        $this->actionType = REQUEST_TYPE_AJAX;
        $type = $_GET['type'];
        $playerId = $this->playerId;
        $playerItem = Yii::app()->objectLoader->load('Item', $playerId);

        $numall = $playerItem->getItemNum(array(35));
        $numQuarter = $playerItem->getItemNum(array(34));
        $data = $this->renderPartial('notice', array('type' => $type, 'num1' => $numall, 'num2' => $numQuarter), true);
        $this->display($data);
    }

    public function actionGoodsUse() {
        if (!(isset($_REQUEST['mailId']))) {
            $this->error('mailId is null');
        } else {
            $mailId = $_REQUEST['mailId'];
        }
        if (!(isset($_REQUEST['goodsId']))) {
            $this->error('goodsId is null');
        } else {
            $goodsId = $_REQUEST['goodsId'];
        }
        if (!(isset($_REQUEST['itemId']))) {
            $this->error('itemId is null');
        } else {
            $itemId = $_REQUEST['itemId'];
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $shopModel = Yii::app()->objectLoader->load('ShopModel', $this->playerId);
            $shopModel->buyGoods($goodsId);
            $itemModel = Yii::app()->objectLoader->load('ItemModel', $this->playerId);
            $itemModel->itemUse($itemId, $mailId);
            $transaction->commit();
            $getDays = Yii::app()->objectLoader->load('Mail', $mailId)->getDays;
            if ($getDays <= time()) {
                //快速投递成就检查
                $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_STAMP);
                $achieveEvent->onAchieveComplete();
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
        $playerMailInfo = $this->mailRefresh(2, $mailId);
        $getDaysView = $this->getDaysRefresh($mailId);
        $params = array(
            'isOk' => true,
            'getDaysView' => $getDaysView,
            'playerMailInfo' => $playerMailInfo,
        );
        $this->display($params);
    }

}

?>
