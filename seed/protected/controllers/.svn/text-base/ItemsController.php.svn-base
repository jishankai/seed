<?php

class ItemsController extends Controller {
    /*
     * 初始化处理方法
     */

    public function init() {
        parent::init();
        $this->attachBehavior('GardenDecoAttributes', new GardenDecoAttributes);
    }

    /*
     * 分类显示装饰
     */

    public function actionDecoShow() {
        $isAjaxShow = isset($_REQUEST['isAjaxShow']) ? intval($_REQUEST['isAjaxShow']) : 0;

        $this->showGlobalMessage = false;

        $this->layout = "//layouts/theme";
        $category = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : 7;
        $itemModel = Yii::app()->objectLoader->load('ItemModel', $this->playerId);
        $addValidate = array();
        $addValidate['littleCount'] = 0;
        $addValidate['middelCount'] = 0;
        $addValidate['bigCount'] = 0;

        if (isset($_REQUEST['gardenId']) && $_REQUEST['gardenId'] != 0) {
            $gardenId = $_REQUEST['gardenId'];
            $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
            $addValidate = $garden->addValidate();
        }

        $categories = array(
            7 => '装饰1',
            8 => '装饰2',
            9 => '装饰3',
            4 => '背景',
            5 => '奖杯',
        );

        if (!isset($categories[$category]))
            $category = 7;
        $decoList = $itemModel->getItemInfo('decoItem', $category);
        $decoListShow = array();
        if ($category == 7 || $category == 8 || $category == 9) {
            for ($i = MIN_DECOID; $i <= MAX_DECOID; $i++) {
                $item = Yii::app()->objectLoader->load('DecoItem', $i);
                $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $i);
                if ($category == $item->category) {
                    if (!isset($decoList[$i])) {
                        $decoListShow[$i] = array('item' => $item, 'num' => 0, 'itemMeta' => $itemMeta);
                    } else {
                        $decoListShow[$i] = $decoList[$i];
                    }
                    $canBuy = Yii::app()->objectLoader->load('ShopModel', $this->playerId)->checkGoods($i, false);
                    $decoListShow[$i]['canBuy'] = (int) $canBuy;

                    if ($item->canBuy == 0) {
                        $decoListShow[$i]['canBuy'] = 0;
                    }
                }
            }
        }

        if ($category == 4) {
            for ($i = MIN_BACKGROUDID; $i <= MAX_BACKGROUDID; $i++) {
                if (!isset($decoList[$i])) {
                    $item = Yii::app()->objectLoader->load('DecoItem', $i);
                    $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $i);
                    $decoListShow[$i] = array('item' => $item, 'num' => 0, 'itemMeta' => $itemMeta);
                } else {
                    $decoListShow[$i] = $decoList[$i];
                }

                $special = $this->GardenDecoAttributes->getSpecial($i);
                $itemMail = Yii::app()->objectLoader->load('DecoItem', $special['decoId1']);
                $decoListShow[$i]['mail'] = str_replace(substr($itemMail->image, -4), '', $itemMail->image);
                $itemShop = Yii::app()->objectLoader->load('DecoItem', $special['decoId2']);
                $decoListShow[$i]['shop'] = str_replace(substr($itemShop->image, -4), '', $itemShop->image);

                $canBuy = Yii::app()->objectLoader->load('ShopModel', $this->playerId)->checkGoods($i, false);
                $decoListShow[$i]['canBuy'] = (int) $canBuy;

                if (isset($item->canBuy) && $item->canBuy == 0) {
                    $decoListShow[$i]['canBuy'] = 0;
                }

                if ($i == $garden->backGround) {
                    $decoListShow[$i]['isSame'] = 1;
                } else {
                    $decoListShow[$i]['isSame'] = 0;
                }
            }
        }

        if ($category == 5) {
            $playerItem = Yii::app()->objectLoader->load('Item', $this->playerId);
            $cupState = $playerItem->getCupState();
            for ($i = MIN_CUPDECOID; $i <= MAX_CUPDECOID; $i++) {
                if (!isset($decoList[$i])) {
                    $item = Yii::app()->objectLoader->load('DecoItem', $i);
                    if ($item->level == 1 && (!isset($cupState[$i]))) {
                        $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $i);
                        $decoListShow[$i] = array('item' => $item, 'num' => 0, 'itemMeta' => $itemMeta);
                    }
                } else {
                    $decoListShow[$i] = $decoList[$i];
                }
            }
        }

        //新手引导
        $guideModel = Yii::app()->objectLoader->load('GuideModel', $this->playerId);
        $currentGuide = $guideModel->isCurrentGuide(60);
        $accessLevel = GuideModel::getAccessLevel($this->playerId);
        if ($accessLevel == 67) {
            $player = Yii::app()->objectLoader->load('Player', $this->playerId);
            $player->setStatus('guideLevel', 69);
            GlobalState::set($this->playerId, 'USER_GUIDE_LEVEL', 67);
        }

        $params = array(
            'categories' => $categories,
            'decoList' => $decoListShow,
            'category' => $category,
            'addValidate' => $addValidate,
            'welcomeAction' => $currentGuide == 1 ? true : false,
            'accessLevel' => $accessLevel,
        );

        if (isset($_REQUEST['gardenId']) && $_REQUEST['gardenId'] != 0) {
            $params['gardenId'] = $_REQUEST['gardenId'];
        }

        if (isset($cupState)) {
            $params['cupState'] = $cupState;
        }

        if ($isAjaxShow == 1) {
            $viewFile = 'decoList';
            $data = $this->renderPartial($viewFile, $params, true);
            $this->display($data);
        } else {
            $this->display('decoList', $params);
        }
    }

    /*
     * 分类显示道具
     */

    public function actionItemShow() {
        $this->layout = "//layouts/theme";
        $category = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : -1;
        $itemModel = Yii::app()->objectLoader->load('ItemModel', $this->playerId);

        $categories = array(
            0 => '道具',
        );

        if (!isset($categories[$category]))
            $category = -1;
        $itemList = $itemModel->getCItemInfo('useItem');

        $params = array(
            'categories' => $categories,
            'itemList' => $itemList,
            'category' => 0,
        );

        if (isset($_REQUEST['ajaxRefresh']) && $_REQUEST['ajaxRefresh'] == 1) {
            $viewFile = 'itemView';
            $data = $this->renderPartial($viewFile, $params, true);
            $this->display($data);
        } else {
            $this->display('itemList', $params);
        }
    }

    public function actionAjaxShow($category) {
        $this->layout = "//layouts/theme";
        if (isset($_REQUEST['category'])) {
            $category = $_REQUEST['category'];
        }
        $itemModel = Yii::app()->objectLoader->load('ItemModel', $this->playerId);

        if ($category == 0) {
            $itemList = $itemModel->getCItemInfo('useItem');
        } else if ($category == 1) {
            $itemList = $itemModel->getCItemInfo('resItem');
        } else if ($category == 3) {
            $itemList = $itemModel->getCItemInfo('chestItem');
        }

        $params = array(
            'itemList' => $itemList,
            'category' => $category,
        );

        $viewFile = 'itemList';
        $data = $this->renderPartial($viewFile, $params, true);
        $this->display($data);
    }

    /*
     * 分类显示使用物品
     */

    public function actionResShow() {
        $this->layout = "//layouts/theme";
        $category = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : -1;
        $itemModel = Yii::app()->objectLoader->load('ItemModel', $this->playerId);

        $categories = array(
            1 => '使用品',
        );

        if (!isset($categories[$category]))
            $category = -1;
        $resList = $itemModel->getCItemInfo('resItem');

        $params = array(
            'categories' => $categories,
            'itemList' => $resList,
            'category' => 1,
        );

        if (isset($_REQUEST['ajaxRefresh']) && $_REQUEST['ajaxRefresh'] == 1) {
            $viewFile = 'itemView';
            $data = $this->renderPartial($viewFile, $params, true);
            $this->display($data);
        } else {
            $this->display('itemList', $params);
        }
    }

    /*
     * 使用物品
     */

    public function actionUse() {
        $userId = isset($_REQUEST['userId']) ? intval($_REQUEST['userId']) : $this->playerId;
        if (!(isset($_REQUEST['itemId']))) {
            $this->error('itemId is null');
        } else {
            $itemId = $_REQUEST['itemId'];
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $itemModel = Yii::app()->objectLoader->load('ItemModel', $this->playerId);
            $useView = '';
            $useArray = $itemModel->itemUse($itemId, $userId);
            $useBind = array(
                'useArray' => $useArray,
                'itemId' => $itemId,
            );
            if (($itemId >= 39 && $itemId <= 41) || $itemId == 47) {
                $useView = $this->renderPartial('showUseInfo', $useBind, true);
            }
            $transaction->commit();
            $params = array(
                'isOk' => true,
                'itemId' => $itemId,
                'n' => isset($_REQUEST['n']) ? intval($_REQUEST['n']) : 0,
                'category' => isset($_REQUEST['category']) ? intval($_REQUEST['category']) : -1,
                'useView' => $useView,
            );
            $this->display($params);
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

    /*
     * 送礼
     */

    public function actionGive() {
        if (!(isset($_REQUEST['friendId']))) {
            $this->error('friendId is null');
        } else {
            $friendId = $_REQUEST['friendId'];
        }
        if (!(isset($_REQUEST['itemId']))) {
            $this->error('itemId is null');
        } else {
            $itemId = $_REQUEST['itemId'];
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
            if ($itemMeta->getCategory() != 1) {
                throw new CException('error category');
            }
            $itemModel = Yii::app()->objectLoader->load('ItemModel', $this->playerId);
            $itemModel->itemPresent($friendId, $itemId);
            $transaction->commit();
            $params = array(
                'isOk' => true,
                'itemId' => $itemId,
                'n' => isset($_REQUEST['n']) ? intval($_REQUEST['n']) : 0,
                'category' => isset($_REQUEST['category']) ? intval($_REQUEST['category']) : -1,
                'give' => true,
            );
            $this->display($params);
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

    /*
     * 卖掉物品
     */

    public function actionSellItemShow() {
        if (!(isset($_REQUEST['itemId']))) {
            $this->error('itemId is null');
        } else {
            $itemId = $_REQUEST['itemId'];
        }
        if (!(isset($_REQUEST['numall']))) {
            $this->error('numall is null');
        } else {
            $numall = $_REQUEST['numall'];
        }
        $this->actionType = REQUEST_TYPE_AJAX;
        $playerId = $this->playerId;
        $playerItem = Yii::app()->objectLoader->load('Item', $playerId);

        //$numall = $playerItem->getItemNum(array($_REQUEST['itemId']));
        $params = array(
            'numall' => $numall,
            'itemId' => $itemId,
            'n' => isset($_REQUEST['n']) ? intval($_REQUEST['n']) : 0,
            'category' => isset($_REQUEST['category']) ? intval($_REQUEST['category']) : -1,
        );
        $data = $this->renderPartial('itemSell', $params, true);
        $this->display($data);
    }

    public function actionSell() {
        $num = 1;
        if (!(isset($_REQUEST['itemId']))) {
            $this->error('itemId is null');
        } else {
            $itemId = $_REQUEST['itemId'];
        }

        if (isset($_REQUEST['num'])) {
            $num = $_REQUEST['num'];
        }

        if (!ereg("^[0-9]*[1-9][0-9]*$", $num) && $num != 0) {
            throw new CException(Yii::t('Item', 'num error'));
        }

        $transaction = Yii::app()->db->beginTransaction();
        try {
            $itemModel = Yii::app()->objectLoader->load('ItemModel', $this->playerId);
            $itemModel->itemSell($itemId, $num);
            $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
            $sellPrice = $itemMeta->getSellPrice();
            $transaction->commit();
            $category = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : -1;
//            if ($category == 1 || $category == 0) {
//                $this->actionAjaxShow($category);
//            } else {
            $params = array(
                'isOk' => true,
                'itemId' => $itemId,
                'n' => isset($_REQUEST['n']) ? intval($_REQUEST['n']) : 0,
                'category' => isset($_REQUEST['category']) ? intval($_REQUEST['category']) : -1,
                'sellPrice' => $sellPrice,
                'num' => $num,
                'sell' => true,
            );
            $this->display($params);
//            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

}

?>