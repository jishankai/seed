<?php

class GardenController extends Controller {
    /*
     * 初始化处理方法
     */

    public function init() {
        parent::init();
    }

    public function actionFosterSeed() {
        $gardenId = isset($_REQUEST['gardenId']) ? intval($_REQUEST['gardenId']) : 0;
        if (!empty($gardenId)) {
            $params = array(
                'gardenId' => $gardenId,
            );
            $this->display('fosterSeed', $params);
        }
    }

    /*
     * 获取购买花园的价格
     */

//    public function actionGardenPrice() {
//        $gardenModel = Yii::app()->objectLoader->load('GardenModel', $this->playerId);
//        $this->display('gardenPrice', array('price' => $gardenModel->getGardenPrice()));
//    }

    /*
     * 通过Ajax获取花园价格
     */

    public function actionAjaxGPrice() {
        $gardenModel = Yii::app()->objectLoader->load('GardenModel', $this->playerId);
        $info = $gardenModel->useGardenPrice();
        if (!empty($_REQUEST['display'])) {
            $info = nl2br(print_r($info, true));
        }
        $this->display($info);
    }

    /*
     * 显示花园列表
     */

    public function actionGardenList() {
        $selectGardenId = 0;
        if (isset($_REQUEST['selectGardenId'])) {
            $_SESSION['selectGardenId'] = $_REQUEST['selectGardenId'];
        }
        if (isset($_SESSION['selectGardenId'])) {
            $selectGardenId = $_SESSION['selectGardenId'];
        }
        $shakeSeedId = 0;
        if (isset($_REQUEST['shakeSeedId'])) {
            $shakeSeedId = $_REQUEST['shakeSeedId'];
        }
        $this->layout = "//layouts/theme";
        $playerId = !empty($_REQUEST['playerId']) ? $_REQUEST['playerId'] : $this->playerId;
        $showFlag = (!empty($_REQUEST['playerId']) && ($_REQUEST['playerId'] != $this->playerId)) ? false : true;
        if (!empty($_REQUEST['playerId'])) {
            if (PlayerFriend::abletoVisit($this->playerId, $_REQUEST['playerId']) == 3) {
                throw new CException('you can not visit the gardenList,you are not friend');
            }
        }
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        $gardenModel = Yii::app()->objectLoader->load('GardenModel', $playerId);
        $price = $gardenModel->getGardenPrice();
        $gardens = $gardenModel->getGardenInfoAll();
        foreach ($gardens as $index => $garden) {
            $gardens[$index]->seedArrayList = explode(',', $garden->seedList);
        }

        //新手引导
        $guideModel = Yii::app()->objectLoader->load('GuideModel', $this->playerId);
        $currentGuide = $guideModel->isCurrentGuide(120);
        $accessLevel = GuideModel::getAccessLevel($this->playerId);

        $params = array('allGardens' => $gardens,
            'gardenPrice' => $price,
            'playerName' => $player->playerName,
            'showFlag' => $showFlag,
            'shakeSeedId' => $shakeSeedId,
            'selectGardenId' => $selectGardenId,
            'player' => $player,
            'welcomeAction' => $currentGuide,
            'gardenCount' => $player->gardenNum,
        );

        //新手引导
        $params['welcomeAction'] = $currentGuide == 1 ? true : false;
        $params['accessLevel'] = $accessLevel;

        $this->display('gardenInfoMulti', $params);
    }

    //虚拟好友花园列表
    public function actionVisualGardenList() {
        $this->layout = "//layouts/theme";

        $selectGardenId = 0;
        if (isset($_REQUEST['selectGardenId'])) {
            $selectGardenId = $_REQUEST['selectGardenId'];
        }

        //获得花园信息
        //包括gardenId花园ID
        //seedArrayList花园中含有的种子对象
        //playerId所有者ID，也就是虚拟好友ID
        //gardenSign花园标识 1到10
        //backGround花园背景
        //decoExtraGrow成长值
        //favouriteFlag是否是最喜爱的花园
        if (!empty($selectGardenId)) {
            $id = VisualFriend::checkOwnerByGarden($selectGardenId);
            if ($id) {
                $key = VisualFriend::createKey($id, $this->playerId);
                $visualFriend = Yii::app()->objectLoader->load('VisualFriend', $key);

                $playerName = $visualFriend->playerName;
                $gardens = $visualFriend->getGardens();
                foreach ($gardens as $index => $garden) {
                    $gardens[$index]->seedArrayList = explode(',', $garden->seedList);
                }

                $this->display('gardenInfoMulti', array(
                    'isVisual' => true,
                    'allGardens' => $gardens,
                    'playerName' => $playerName,
                    'showFlag' => false,
                    'selectGardenId' => $selectGardenId,
                    'welcomeAction' => false,
                ));
            } else {
                throw new SException(Yii::t('VisualFriend', 'Visual Friend Error'));
            }
        } else {
            throw new SException(Yii::t('Garden', 'Garden Error'));
        }
    }

    public function actionLittleList() {
        $callbackUrl = '';
        if (isset($_REQUEST['callbackUrl'])) {
            $callbackUrl = $_REQUEST['callbackUrl'];
        }
        $growPeriod = 0;
        if (isset($_REQUEST['growPeriod'])) {
            $growPeriod = $_REQUEST['growPeriod'];
        }
        $this->layout = "//layouts/theme";
        $playerId = !empty($_REQUEST['playerId']) ? $_REQUEST['playerId'] : $this->playerId;
        $showFlag = (!empty($_REQUEST['playerId']) && ($_REQUEST['playerId'] != $this->playerId)) ? false : true;
        if (!empty($_REQUEST['playerId'])) {
            if (PlayerFriend::abletoVisit($this->playerId, $_REQUEST['playerId']) == 3) {
                throw new CException('you can not visit the gardenList,you are not friend');
            }
        }
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        $gardenModel = Yii::app()->objectLoader->load('GardenModel', $playerId);
        $gardens = $gardenModel->getGardenInfoAll();
        foreach ($gardens as $index => $garden) {
            $gardens[$index]->seedArrayList = explode(',', $garden->seedList);
        }
        $params = array('allGardens' => $gardens,
            'playerName' => $player->playerName,
            'showFlag' => $showFlag,
            'callbackUrl' => $callbackUrl,
            'isSelectSeed' => !empty($_REQUEST['selectType']) && $_REQUEST['selectType'] == 'seed',
            'growPeriod' => $growPeriod,
        );
        if (isset($_REQUEST['showtype']) && $_REQUEST['showtype'] == 'url') {
            $this->display('littleGardenList', $params);
        } else {
            $data = $this->renderPartial('littleGardenList', $params, true);
            $this->display($data);
        }
    }

    /*
     * 更换花园背景
     */

    public function actionChangeGround() {
        if (!(isset($_REQUEST['gardenId']))) {
            $this->error('gardenId is null');
        }
        if (!(isset($_REQUEST['name']))) {
            $this->error('name is null');
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $gardenModel = Yii::app()->objectLoader->load('GardenModel', $this->playerId);
            $id = $gardenModel->getDecoId($_REQUEST['name']);
            $gardenModel->setBackGround($_REQUEST['gardenId'], $id);
            $transaction->commit();
            //花园背景成就
            $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_DIFFBACK);
            $achieveEvent->onAchieveComplete();
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
        $this->actionDecoInfo($_REQUEST['gardenId']);
    }

    /*
     * 获取奖励
     */

    public function actionGetReward() {
        if (!(isset($_REQUEST['index']))) {
            $this->error('index is null');
        }
        $index = $_REQUEST['index'];
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $loginRewardModel = Yii::app()->objectLoader->load('LoginRewardModel', $this->playerId);
            $rewardIndex = $loginRewardModel->getRewardIndex(1);
            $reward = $loginRewardModel->getReward($rewardIndex);
            $loginRewardModel->useReward($rewardIndex);
            $getInfo = $loginRewardModel->setRewards($rewardIndex);
            GlobalState::set($this->playerId, 'REWARD_INDEX', 1);
            $transaction->commit();
            $params = array(
                'isOk' => true,
                'reward' => $reward,
                'getInfo' => $getInfo,
                'index' => $index,
            );
            $this->display('showReward', $params);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if ($message == 'resItem' || $message == 'useItem') {
                $params = array(
                    'type' => $message,
                );
                $this->display('ItemFull', $params);
            }
        }
    }

//    /*
//     * 种子打回
//     */
//
//    public function actionSeedBack() {
//        if (!(isset($_REQUEST['seedId']))) {
//            $this->error('seedId is null');
//        }
//        if (!(isset($_REQUEST['fromGradenId']))) {
//            $this->error('fromGradenId is null');
//        }
//        $transaction = Yii::app()->db->beginTransaction();
//        try {
//            $fosterModel = Yii::app()->objectLoader->load('FosterModel', $this->playerId);
//            $seed = Yii::app()->objectLoader->load('Seed', $_REQUEST['seedId']);
//            $fosterModel->seedToMail($_REQUEST['seedId'], $this->playerId);
//            $transaction->commit();
//            $params = array(
//                'isOk' => true,
//                'seedId' => $_REQUEST['seedId'],
//                'fromGradenId' => $seed->gardenId,
//            );
//            $this->display($params);
//        } catch (Exception $ex) {
//            $transaction->rollBack();
//            throw $ex;
//        }
//    }

    /*
     * 设置最喜爱的花园
     */

    public function actionFavourite() {
        if (!(isset($_REQUEST['gardenId']))) {
            $this->error('gardenId is null');
        }
        if (!(isset($_REQUEST['loveSeedId']))) {
            $this->error('loveSeedId is null');
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $gardenModel = Yii::app()->objectLoader->load('GardenModel', $this->playerId);
            $gardenModel->setFavorteGarden($_REQUEST['gardenId']);

            if ($_REQUEST['loveSeedId'] != 0) {
                $seedModel = Yii::app()->objectLoader->load('SeedModel', $this->playerId);
                $seedModel->setFavouriteSeed($_REQUEST['loveSeedId']);
            }
            $transaction->commit();
            $params = array(
                'isOk' => true,
                'gardenId' => $_REQUEST['gardenId'],
                'loveSeedId' => $_REQUEST['loveSeedId'],
            );
            $this->display($params);
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

    /*
     * 创建花园
     */

    public function actionCreate() {
        $this->showGlobalMessage = false;

        $transaction = Yii::app()->db->beginTransaction();
        try {
            $gardenModel = Yii::app()->objectLoader->load('GardenModel', $this->playerId);
            $newGardenId = $gardenModel->getNewGarden();
            $transaction->commit();
            $price = $gardenModel->getGardenPrice();
            $params = array(
                'isOk' => true,
                'newGardenId' => $newGardenId,
                'gardenPrice' => $price,
                'showFlag' => true,
            );
            $newGardenHtml = $this->renderPartial('newGarden', $params, true);
            $newBuyGardenHtml = $this->renderPartial('buyGarden', $params, true);
            $data = array(
                'newGardenHtml' => $newGardenHtml,
                'newBuyGardenHtml' => $newBuyGardenHtml,
            );
            //花园背景成就
            $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_DIFFBACK);
            $achieveEvent->onAchieveComplete();
            //新手引导保存状态
            $guideModel = Yii::app()->objectLoader->load('GuideModel', $this->playerId);
            $currentGuide = $guideModel->isCurrentGuide(120);
            if ($currentGuide == 1) {
                $guideModel->saveStatus(121);
                GuideModel::checkGuideState($this->playerId);
            }
            $this->display($data);
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

    public function actionRefreshCreate() {
        try {
            $gardenModel = Yii::app()->objectLoader->load('GardenModel', $this->playerId);
            $price = $gardenModel->getGardenPrice();
            $params = array(
                'isOk' => true,
                'gardenPrice' => $price,
                'showFlag' => true,
            );
            $newBuyGardenHtml = $this->renderPartial('buyGarden', $params, true);
            $data = array(
                'newBuyGardenHtml' => $newBuyGardenHtml,
            );
            $this->display($data);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function actionDecoBuy() {
        if (!(isset($_REQUEST['gardenId']))) {
            $this->error('gardenId is null');
        }
        if (!(isset($_REQUEST['name']))) {
            $this->error('name is null');
        }
        if (!(isset($_REQUEST['x']))) {
            $this->error('x is null');
        }
        if (!(isset($_REQUEST['y']))) {
            $this->error('y is null');
        }
        if (!(isset($_REQUEST['direction']))) {
            $this->error('direction is null');
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $gardenModel = Yii::app()->objectLoader->load('GardenModel', $this->playerId);
            $id = $gardenModel->getDecoId($_REQUEST['name']);
            $shopModel = Yii::app()->objectLoader->load('ShopModel', $this->playerId);
            $shopModel->buyGoods($id);
            $this->AddDecoToGarden($_REQUEST['gardenId'], $_REQUEST['name'], $_REQUEST['x'], $_REQUEST['y'], $_REQUEST['direction']);
            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw new CException(Yii::t('item', 'you have not enough money to buy the decoration'));
        }

        $id = $gardenModel->getDecoId($_REQUEST['name']);

        $this->actionDecoInfo($_REQUEST['gardenId'], 'DecoBuy', $id);
    }

    /*
     * 购买背景
     */

    public function actionGroundBuy() {
        if (!(isset($_REQUEST['gardenId']))) {
            $this->error('gardenId is null');
        }
        if (!(isset($_REQUEST['name']))) {
            $this->error('name is null');
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $gardenModel = Yii::app()->objectLoader->load('GardenModel', $this->playerId);
            $id = $gardenModel->getDecoId($_REQUEST['name']);
            $shopModel = Yii::app()->objectLoader->load('ShopModel', $this->playerId);
            $shopModel->buyGoods($id);
            $gardenModel->setBackGround($_REQUEST['gardenId'], $id);
            $transaction->commit();
            //花园背景成就
            $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_DIFFBACK);
            $achieveEvent->onAchieveComplete();
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
        $this->actionDecoInfo($_REQUEST['gardenId'], 'GroundBuy', $id);
    }

    /*
     * 装饰品摆放确认**
     */

    public function AddDecoToGarden($gardenId, $name, $x, $y, $direction) {
        $gardenModel = Yii::app()->objectLoader->load('GardenModel', $this->playerId);
        $id = $gardenModel->getDecoId($name);
        $decoration = Yii::app()->objectLoader->load('DecoItem', $id);
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        //$gardenId, $decoration, $x x轴的起始坐标, $y y轴的起始坐标
        $garden->MoveDecorationToGarden($this->playerId, $gardenId, $decoration, $x, $y, $direction);

        if ($decoration->category == 5) {
            $item = Yii::app()->objectLoader->load('Item', $this->playerId);
            $item->changeCupState($id, 0);
        }

        //任务检查
        $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_DECO);
        $missionEvent->onMissionComplete();
    }

    public function actionAddDeco() {
        if (!(isset($_REQUEST['gardenId']))) {
            $this->error('gardenId is null');
        }
        if (!(isset($_REQUEST['name']))) {
            $this->error('name is null');
        }
        if (!(isset($_REQUEST['x']))) {
            $this->error('x is null');
        }
        if (!(isset($_REQUEST['y']))) {
            $this->error('y is null');
        }
        if (!(isset($_REQUEST['direction']))) {
            $this->error('direction is null');
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $this->AddDecoToGarden($_REQUEST['gardenId'], $_REQUEST['name'], $_REQUEST['x'], $_REQUEST['y'], $_REQUEST['direction']);
            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        $gardenModel = Yii::app()->objectLoader->load('GardenModel', $this->playerId);
        $id = $gardenModel->getDecoId($_REQUEST['name']);

        //奖杯成就检查
        $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_CUP, array('decoId' => $id));
        $achieveEvent->onAchieveComplete();

        $guideModel = Yii::app()->objectLoader->load('GuideModel', $this->playerId);
        $currentGuide = $guideModel->isCurrentGuide(60);
        $this->actionDecoInfo($_REQUEST['gardenId'], 'AddDeco', $id, $currentGuide == 1 ? 67 : 0);
    }

    /*
     * 获取花园装饰信息**
     */

    //提供给虚拟花园使用
    public function actionVisualDecoInfo() {
        if (!(isset($_REQUEST['gardenId']))) {
            $this->error('gardenId is null');
        }

        $this->actionDecoInfo($_REQUEST['gardenId']);
    }

    public function actionDecoInfo($gardenId = 0, $decoAction = '', $decoId = 0, $accessLevel = 0) {
        if ($gardenId == 0) {
            if (!(isset($_REQUEST['playerId']))) {
                $playerId = $this->playerId;
            } else {
                if (PlayerFriend::abletoVisit($this->playerId, $_REQUEST['playerId']) == 3) {
                    $this->error(Yii::t('Garden', 'you can not visit the gardenList,you are not friend'));
                    throw new CException('you can not visit this garden,you are not friend');
                }
                $playerId = $_REQUEST['playerId'];

                $isFriend = PlayerFriend::isFriend($this->playerId, $_REQUEST['playerId']);
                if ($isFriend != 5) {
                    //好友拜访成就检查
                    $achieveEvent = new AchievementEvent($_REQUEST['playerId'], ACHIEVEEVENT_VISITED);
                    $achieveEvent->onAchieveComplete();
                    //任务检查
                    $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_VISIT);
                    $missionEvent->onMissionComplete();
                    $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_FRIENDVISIT, array('behavior' => 'visit', 'friendId' => $playerId));
                    $missionEvent->onMissionComplete();
                }
            }
            if (!(isset($_REQUEST['gardenSign']))) {
                $gardenId = -1;
            } else {
                $gardenId = (int) Garden::getGardenId($playerId, $_REQUEST['gardenSign']);
            }
        } else {
            $playerId = $this->playerId;
        }

        $player = Yii::app()->objectLoader->load('Player', $playerId);

        if ($gardenId == -1) {
            $gardenId = (int) $player->favouriteGarden;
        }

        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        $actionPoint = $player->getPlayerPoint('actionPoint')->getValue();
        $actionPointMax = $player->getPlayerPoint('actionPoint')->getMax();
        $userMoney = $player->getUserMoney();
        $nextLevelExp = $player->nextLevelExp();
        $supplyPower = $player->getPlayerPoint('supplyPower');
        $addValidate = $garden->addValidate();
        $loginRewardModel = Yii::app()->objectLoader->load('LoginRewardModel', $playerId);
        $rewards = $loginRewardModel->getRewards();
        $rewardsCount = $loginRewardModel->getRewardsCount();
        $gardenDecoShow = array();
        if ($decoAction == '') {
            $gardenDecoShow = $garden->getGardenDecoShow();
        }
        $gardenType = $garden->gardenType($this->playerId, $garden->playerId);
        $gardenDecoShow['type'] = $gardenType;

        $mailModel = Yii::app()->objectLoader->load('MailModel', $this->playerId);
        $allUnReadCount = $mailModel->getAllUnReadMailFlag();
        $haveNewMail = $allUnReadCount == true ? 1 : 0;

//        $openPhotoShow = false;
//        $snsModel = Yii::app()->objectLoader->load('SnsModel', $this->playerId);
//        $stateInfo = $snsModel->getStateInfo();
//        if ($stateInfo['isTwitterShare'] == 1 || $stateInfo['isTwitterShare'] == 1) {
//            $openPhotoShow = true;
//        }

        $array = array(
            'refreshgold' => $player->gold,
            'refreshmoney' => $userMoney,
            'refreshexp' => $player->exp,
            'refreshmaxexp' => $nextLevelExp,
            'refreshlevel' => $player->level,
            'forcerefresh' => 1,
            'refreshenergy' => $supplyPower->getRemainTime(),
            'refreshmaxenergy' => $supplyPower->getMaxTime(),
            'gardenSign' => $garden->gardenSign,
            'refreshgrowth' => $garden->decoExtraGrow,
            'refreshsmalldecocount' => $addValidate['littleCount'],
            'refreshmiddledecocount' => $addValidate['middelCount'],
            'refreshlargedecocount' => $addValidate['bigCount'],
            'haveNewMail' => $haveNewMail,
//            'openPhotoShow' => $openPhotoShow,
        );

        if ($accessLevel != 0) {
            $player->setStatus('guideLevel', $accessLevel);
            $array['accessLevel'] = $accessLevel;
        }

        if ($actionPoint < $actionPointMax) {
            $array['refreshaptime'] = $player->getPlayerPoint('actionPoint')->getRemainTime();
        }
        if ($garden->playerId == $this->playerId) {
            $array['refreshap'] = $actionPoint;
            $array['refreshmaxap'] = $actionPointMax;
            $array['userguide'] = GuideModel::getGuideState($this->playerId);
            if ($accessLevel == 67) {
                $array['userguide'] = 0;
            } else {
                $array['userguide'] = GuideModel::getGuideState($this->playerId);
            }
            $array['missionCount'] = (int) MissionModel::recordsCount($this->playerId);
            $array['haveNewMission'] = (MissionModel::haveNew($this->playerId) == true) ? 1 : 0;
            $gardenDecoShow['rewardsCount'] = $rewardsCount;
        } else {
            //是否是好友确认状态
            $gardenDecoShow['isVerifyFriend'] = (PlayerFriend::isFriend($this->playerId, $garden->playerId) == 1) ? 1 : 0;
            $gardenDecoShow['charged'] = (PlayerFriend::isChargedToday($this->playerId, $garden->playerId) == true) ? 1 : 0;
        }
        GlobalState::set($this->playerId, 'CURRENT_PLAYER', $playerId);
        GlobalState::set($this->playerId, 'FOSTER_STATE', $garden->getFosterState($this->playerId));

        if ($decoId != 0) {
            $decoration = Yii::app()->objectLoader->load('DecoItem', $decoId);
        }
        //刷新特效
        if ($decoAction == 'AddDeco') {
            $playerItem = Yii::app()->objectLoader->load('Item', $playerId);
            $gardenDecoShow['name'] = str_replace(substr($decoration->image, -4), '', $decoration->image);
            $gardenDecoShow['count'] = $playerItem->getItemNum(array($decoId));
            $gardenDecoShow['price'] = $decoration->sellPrice;
            $gardenDecoShow['isFull'] = 'false';
            if ($decoration->category == 7 || $decoration->category == 8 || $decoration->category == 9) {
                if (($addValidate['littleCount'] >= MAXLITTLEDECONUM && $decoration->sizeType == 1) || ($addValidate['middelCount'] >= MAXMIDDELDECONUM && $decoration->sizeType == 2) || ($addValidate['bigCount'] >= MAXBIGDECONUM && $decoration->sizeType == 3) || $gardenDecoShow['count'] == 0) {
                    $gardenDecoShow['isFull'] = 'true';
                }
            }

            $gardenDecoShow['growChange'] = $decoration->grow;
        }

        if ($decoAction == 'DelDeco') {
            $gardenDecoShow['growChange'] = -1 * (int) $decoration->grow;
            $gardenDecoShow['goldChange'] = $decoration->sellPrice;
        }

        if ($decoAction == 'DecoToItem') {
            $gardenDecoShow['growChange'] = -1 * (int) $decoration->grow;
        }

        if ($decoAction == 'GroundBuy') {
            if ($decoration->moneyType == 1) {
                $gardenDecoShow['moneyChange'] = -1 * (int) $decoration->price;
            } else if ($decoration->moneyType == 0) {
                $gardenDecoShow['goldChange'] = -1 * (int) $decoration->price;
            }
        }

        //此段是再次购买的
        if ($decoAction == 'DecoBuy') {
            $playerItem = Yii::app()->objectLoader->load('Item', $playerId);
            $data = array();
            $data['name'] = str_replace(substr($decoration->image, -4), '', $decoration->image);
            $data['count'] = $playerItem->getItemNum(array($decoId));
            $data['price'] = $decoration->sellPrice;
            $data['isFull'] = 'false';

            $canBuy = (int) Yii::app()->objectLoader->load('ShopModel', $this->playerId)->checkGoods($decoId, false);

            if ($decoration->category == 5) {
                if (($addValidate['littleCount'] >= MAXLITTLEDECONUM && $decoration->sizeType == 1) || ($addValidate['middelCount'] >= MAXMIDDELDECONUM && $decoration->sizeType == 2) || ($addValidate['bigCount'] >= MAXBIGDECONUM && $decoration->sizeType == 3)) {
                    $data['isFull'] = 'true';
                }
            }

            if ($decoration->category == 7 || $decoration->category == 8 || $decoration->category == 9) {
                if (($addValidate['littleCount'] >= MAXLITTLEDECONUM && $decoration->sizeType == 1) || ($addValidate['middelCount'] >= MAXMIDDELDECONUM && $decoration->sizeType == 2) || ($addValidate['bigCount'] >= MAXBIGDECONUM && $decoration->sizeType == 3) || ($data['count'] == 0 && $canBuy == 0)) {
                    $data['isFull'] = 'true';
                }
            }

            //刷新特效
            $data['growChange'] = $decoration->grow;
            if ($decoration->moneyType == 1) {
                $data['moneyChange'] = -1 * (int) $decoration->price;
            } else if ($decoration->moneyType == 0) {
                $data['goldChange'] = -1 * (int) $decoration->price;
            }
            $this->display($data, array('callback' => $array));
        }

        //新手引导状态
        foreach (GuideModel::getGuideStateData($this->playerId) as $key => $value) {
            if ($playerId != $this->playerId && $key != 'accessLevel') {
                continue;
            }
            GlobalState::set($this->playerId, $key, $value);
        }
        $this->display($gardenDecoShow, array('callback' => $array));
    }

    /*
     * 卖掉装饰**
     */

    public function actionDelDeco() {
        if (!(isset($_REQUEST['gardenId']))) {
            $this->error('gardenId is null');
        }
        if (!(isset($_REQUEST['x']))) {
            $this->error('x is null');
        }
        if (!(isset($_REQUEST['y']))) {
            $this->error('y is null');
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $garden = Yii::app()->objectLoader->load('Garden', $_REQUEST['gardenId']);
            $id = $garden->getDecorationID($_REQUEST['x'], $_REQUEST['y']);
            $decoration = Yii::app()->objectLoader->load('DecoItem', $id);
            $player = Yii::app()->objectLoader->load('Player', $this->playerId);
            $player->addGold($decoration->sellPrice);
            $garden->removeBuildingGarden($_REQUEST['gardenId'], $decoration, $_REQUEST['x'], $_REQUEST['y']);
            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
        $this->actionDecoInfo($_REQUEST['gardenId'], 'DelDeco', $id);
    }

    /*
     * 装饰品收仓**
     */

    public function actionDecoToItem() {
        if (!(isset($_REQUEST['gardenId']))) {
            $this->error('gardenId is null');
        }
        if (!(isset($_REQUEST['x']))) {
            $this->error('x is null');
        }
        if (!(isset($_REQUEST['y']))) {
            $this->error('y is null');
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $garden = Yii::app()->objectLoader->load('Garden', $_REQUEST['gardenId']);
            $id = $garden->getDecorationID($_REQUEST['x'], $_REQUEST['y']);
            $decoration = Yii::app()->objectLoader->load('DecoItem', $id);
            //$playerId, $gardenId, $decoration, $x x轴的起始坐标, $y y轴的起始坐标
            $garden->MoveDecorationToItem($this->playerId, $_REQUEST['gardenId'], $decoration, $_REQUEST['x'], $_REQUEST['y']);
            if ($decoration->category == 5) {
                $item = Yii::app()->objectLoader->load('Item', $this->playerId);
                $item->changeCupState($id, 2);
            }
            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
        $this->actionDecoInfo($_REQUEST['gardenId'], 'DecoToItem', $id);
    }

    /*
     * 装饰品移动**
     */

    public function actionMoveDeco() {
        if (!(isset($_REQUEST['gardenId']))) {
            $this->error('gardenId is null');
        }
        if (!(isset($_REQUEST['fromx']))) {
            $this->error('fromx is null');
        }
        if (!(isset($_REQUEST['fromy']))) {
            $this->error('fromy is null');
        }
        if (!(isset($_REQUEST['tox']))) {
            $this->error('tox is null');
        }
        if (!(isset($_REQUEST['toy']))) {
            $this->error('toy is null');
        }
        if (!(isset($_REQUEST['direction']))) {
            $this->error('direction is null');
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $garden = Yii::app()->objectLoader->load('Garden', $_REQUEST['gardenId']);
            $id = $garden->getDecorationID($_REQUEST['fromx'], $_REQUEST['fromy']);
            $decoration = Yii::app()->objectLoader->load('DecoItem', $id);
            //$gardenId, $decoration, $x x轴的起始坐标, $y y轴的起始坐标
            $garden->MoveDecorationAtGarden($_REQUEST['gardenId'], $decoration, $_REQUEST['fromx'], $_REQUEST['fromy'], $_REQUEST['tox'], $_REQUEST['toy'], $_REQUEST['direction']);
            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
        $this->actionDecoInfo($_REQUEST['gardenId']);
    }

    /*
     * 移动种子
     */

    public function actionMoveSeed() {
        if (!(isset($_REQUEST['seedId']))) {
            $this->error('seedId is null');
        }
        if (!(isset($_REQUEST['toGarden']))) {
            $this->error('toGarden is null');
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $seed = Yii::app()->objectLoader->load('Seed', $_REQUEST['seedId']);
            if ($seed->gardenId == $_REQUEST['toGarden']) {
                return;
            }
            $gardenModel = Yii::app()->objectLoader->load('GardenModel', $this->playerId);
            $seed = Yii::app()->objectLoader->load('Seed', $_REQUEST['seedId']);
            $gardenModel->seedMove($_REQUEST['seedId'], $seed->gardenId, $_REQUEST['toGarden']);
            $transaction->commit();
            $params = array(
                'isOk' => true,
                'seedId' => $_REQUEST['seedId'],
                'fromGarden' => $seed->gardenId,
                'toGarden' => $_REQUEST['toGarden'],
            );
            //新手引导保存状态
            $guideModel = Yii::app()->objectLoader->load('GuideModel', $this->playerId);
            $currentGuide = $guideModel->isCurrentGuide(120);
            if ($currentGuide == 1) {
                $guideModel->saveStatus();
                GuideModel::checkGuideState($this->playerId);
            }
            $this->display($params);
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

}

?>
