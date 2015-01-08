<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/theme';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    public $playerId;
    public $actionType = REQUEST_TYPE_NORMAL;
    public $showGlobalMessage = true;
    public $showGlobalState = true;

    public function init() {
        if (!empty($_REQUEST['noMessage'])) {
            $this->showGlobalMessage = false;
        }
        if (!empty($_REQUEST['isAjax'])) {
            $this->actionType = REQUEST_TYPE_AJAX;
        } elseif (!empty($_REQUEST['isApi'])) {
            $this->showGlobalMessage = false;
            $this->actionType = REQUEST_TYPE_NATIVE_API;
            $_POST = $_GET = array_merge($_GET, $_POST);
        }
    }

    public function redirect($url, $terminate = true, $statusCode = 302) {
        if ($this->actionType == REQUEST_TYPE_AJAX) {
            $this->echoJsonData(array('url' => $url), 999);
        } else if ($this->actionType == REQUEST_TYPE_NATIVE_API) {
            $this->echoJsonData(array('url' => $url), 999, array('message' => 'redirect'));
        } else {
            Yii::app()->getRequest()->redirect($url, $terminate, $statusCode);
        }
    }

    protected function display($data = array(), $params = array()) {
        switch ($this->actionType) {
            case REQUEST_TYPE_NATIVE_API :
                if( is_array($data) ) {
                    $data = (object)$data ;
                }
                $params['SID'] = Yii::app()->session->getSessionId();
            case REQUEST_TYPE_AJAX :
                $params['globalMessage'] = $this->loadGlobalMessage();

                if (!empty($this->playerId)) {
                    $state = Yii::app()->objectLoader->load('GlobalState', $this->playerId);
                    $params['callback'] = empty($params['callback']) ? $state->getApiData() : array_merge($state->getApiData(), $params['callback']);
                }
                if (isset($params['callback']) && count($params['callback']) == 0) {
                    unset($params['callback']);
                }
                $this->echoJsonData($data, 1, '', $params);
                break;
            default:
                Yii::app()->params['globalMessage'] = $this->loadGlobalMessage();
                if (!empty($this->playerId)) {
                    $state = Yii::app()->objectLoader->load('GlobalState', $this->playerId);
                    Yii::app()->params['callback'] = empty(Yii::app()->params['callback']) ? $state->getApiData() : array_merge($state->getApiData(), Yii::app()->params['callback']);
                }

                $this->echoHtmlData($data, $params);
        }
    }

    protected function echoJsonData($data = array(), $stateCode = 1, $message = 'success', $params = array()) {
        $result = array('stateCode' => $stateCode, 'message' => $message, 'data' => $data);
        if (!empty($params)) {
            $result = array_merge($result, $params);
        }
        if (isset($_GET['showData'])) {
            self::dump($result);
        } else {
            echo json_encode($result);
        }
        Yii::app()->end();
    }

    protected function echoHtmlData($view, $data = array()) {
        if (!empty($view)) {
            $this->render($view, $data);
        }
    }

    protected function error($message = 'system error', $stateCode = -1, $data = array()) {
        $this->showGlobalMessage = false ;
        switch ($this->actionType) {
            case REQUEST_TYPE_NATIVE_API :
            case REQUEST_TYPE_AJAX :

                switch ($stateCode) {
                    case EXCEPTION_TYPE_ITEM_FULL :
                        $player = Yii::app()->objectLoader->load('Player', $this->playerId);
                        $params = array(
                            'player' => $player,
                            'isFull' => 1,
                            'type' => $message,
                        );
                        $message = $this->renderPartial('/globalMessage/ItemFull', $params, true);
                        break;
                    case EXCEPTION_TYPE_AP_NOT_ENOUGH :
                        $playerItem = Yii::app()->objectLoader->load('Item',$this->playerId);
                        $restoreAllItem = 
                        $restoreAllGoods = Yii::app()->objectLoader->load('ShopGoods',90001);
                        $restoreHalfGoods = Yii::app()->objectLoader->load('ShopGoods',90002);
                        $restoreAllItem = Yii::app()->objectLoader->load('ItemMeta',38);
                        $restoreHalfItem = Yii::app()->objectLoader->load('ItemMeta',37);
                        $params = array(
                            'num1'  => $playerItem->getItemNum( array($restoreAllItem->id) ),
                            'num2'  => $playerItem->getItemNum( array($restoreHalfItem->id) ) ,
                            'restoreAllGoods'   => $restoreAllGoods ,
                            'restoreHalfGoods'  => $restoreHalfGoods ,
                            'restoreAllItem'   => $restoreAllItem ,
                            'restoreHalfItem'  => $restoreHalfItem ,
                        ) ;

                        $message = $this->renderPartial('/globalMessage/ActionPoint', $params, true);
                        break;
                    case EXCEPTION_TYPE_GOLD_NOT_ENOUGH :
                        $player = Yii::app()->objectLoader->load('Player', $this->playerId);
                        $params = array(
                            'player' => $player,
                        );
                        $message = $this->renderPartial('/globalMessage/PlayerGold', $params, true);
                        break;
                    case EXCEPTION_TYPE_MONEY_NOT_ENOUGH :
                        $player = Yii::app()->objectLoader->load('Player', $this->playerId);
                        $params = array(
                            'player' => $player,
                        );
                        $message = $this->renderPartial('/globalMessage/PlayerMoney', $params, true);
                        break;
                    case EXCEPTION_TYPE_BREED_CDTIME :
                        $params = array(
                            
                        );
                        $message = $this->renderPartial('/globalMessage/breedCDTime', $params, true);
                        break;
                }
                if (!empty($this->playerId)) {
                    $state = Yii::app()->objectLoader->load('GlobalState', $this->playerId);
                    $resultParams['callback'] = $state->getApiData() ;
                }
                else {
                    $resultParams = array() ;
                }
                $this->echoJsonData($data, $stateCode, $message,$resultParams);
                break;
            default:
                $this->display('/error/html', array('stateCode' => $stateCode, 'message' => $message));
        }
    }

    public function filters() {

        return array(
            'getPlayerId',
            //'checkSeedGrow',
            'checkURL',
        	'getSize',
        );
    }

    public function filterGetPlayerId($filterChain) {
        $session = Yii::app()->session;
        $session->open();

        $isPlayerLogin = false ;
        if (isset($session['playerId'])) {
            $player = Yii::app()->objectLoader->load('Player',$session['playerId']);
            if( !empty($player->sessionId)&&$player->sessionId == $session->getSessionId() ){
                $this->playerId = $session['playerId'];
                $isPlayerLogin = true ;
            }
        } 
        if( $isPlayerLogin ) {
            GlobalState::set($this->playerId,'CURRENT_PLAYER',$this->playerId);
            $filterChain->run();
        }
        else {
            if($this->actionType === REQUEST_TYPE_NATIVE_API) {
                $this->display(array(),array('callback'=>array('relogin'=>1)));
            }
            else {
                $this->redirect($this->createUrl('login/index'));
            }
            return false;
        }
    }

    public function filterCheckSeedGrow($filterChain) {
        $seedModel = Yii::app()->objectLoader->load('SeedModel', $this->playerId);
        $seedModel->checkSeedGrowState();
        $filterChain->run();
    }

    public function filterCheckURL($filterChain) {
        $guideModel = Yii::app()->objectLoader->load( 'GuideModel',$this->playerId );
        if( !$guideModel -> checkGuideUrl( $this->getId(),$this->getAction()->getId() ) ) {
            $this->error('you have no pemission.');
        }
        else {
            $filterChain->run();
        }
    }
    
    public function filterGetSize($filterChain) {
    	$session = Yii::app()->session;
        $session->open();
        
        if(isset($_REQUEST['isipad'])) {
        	Yii::app()->params['size'] = SeedConfig::setSize($_REQUEST['isipad']);
        }elseif (isset($session['size'])) {
        	Yii::app()->params['size'] = $session['size'];
        }
        $filterChain->run();
    }

    public function createUrl($route, $params = array(), $ampersand = '&') {
        $params['SID'] = Yii::app()->session->getSessionId();
        $params['cpid'] = empty($this->playerId)?'0':$this->playerId;
        if ($route === '')
            $route = $this->getId() . '/' . $this->getAction()->getId();
        else if (strpos($route, '/') === false)
            $route = $this->getId() . '/' . $route;
        if ($route[0] !== '/' && ($module = $this->getModule()) !== null)
            $route = $module->getId() . '/' . $route;
        return Yii::app()->createUrl(trim($route, '/'), $params, $ampersand);
    }

    protected static function dump($var, $title = 'DEBUG DUMP') {
        echo "<fieldset><legend style='font-size:14px;color:#f00'> $title </legend><pre style='font-size:12px; color:#666;'>";
        //var_dump($var);
        print_r($var);
        echo "</pre></fieldset>";
    }

    private function loadGlobalMessage() {

        $result = array();
        if (!empty($this->playerId) && $this->showGlobalMessage) {
            $messages = Yii::app()->objectLoader->load('GlobalMessage', $this->playerId)->getMessageData();
            $setting = Yii::app()->objectLoader->load('PlayerSetting', $this->playerId)->getSettingData();
            foreach ($messages as $type => $m) {
                if (empty($m)) {
                    continue;
                }
                switch ($type) {
                    case MESSAGE_TYPE_NEW_MISSION :
                        GlobalState::set($this->playerId, 'REFRESH_MISSION', count($m));
                        break;
                    case MESSAGE_TYPE_NEW_MAIL :
                        GlobalState::set($this->playerId, 'REFRESH_MAIL', count($m));
                        break;
                    case MESSAGE_TYPE_NORMAL : 
                        $result[$type] = $m;
                        break ;
                    case MESSAGE_TYPE_LEVEL_UP :
                        $player = Yii::app()->objectLoader->load('Player', $this->playerId);
                        $params = array(
                            'player' => $player,
                            'levelData' => $player->getLevelUpData() ,
                        );
                        $result[$type] = $this->renderPartial('/globalMessage/levelUp', $params, true);
                        break ;
                    case MESSAGE_TYPE_ITEM_FULL :
                        $player = Yii::app()->objectLoader->load('Player', $this->playerId);
                        $params = array(
                            'player' => $player,
                            'isFull' => 1,
                            'type' => $m[0],
                        );
                        $result[$type] = $this->renderPartial('/globalMessage/ItemFull', $params, true);
                        break ;
                    case MESSAGE_TYPE_ACHIEVE_COMPLETE :
                        if( empty($setting['achievementFlag']) ) {
                            break ;
                        }
                        foreach( $m as $id ) {
                            if( empty($id) ) continue ;
                            $params = array(
                                'achievement' => Yii::app()->objectLoader->load('Achievement',$id) ,
                            );
                            $result[$type][$id] = $this->renderPartial('/globalMessage/AchievementNotice', $params, true);
                        }
                        break ;
                    case MESSAGE_TYPE_MAIL_GIFT : 
                        if( empty($setting['newGiftFlag']) ) {
                            break ;
                        }
                        if( !empty(Yii::app()->params['inGiftBox']) ) {
                            break ;
                        }
                        foreach( $m as $id=>$message ) {
                            $params = array(
                                'message' => $message ,
                            );
                            $result[$type][0] = $this->renderPartial('/globalMessage/NormalNotice', $params, true);
                        }

                        break;
                    case MESSAGE_TYPE_SEED_MOVABLE :
                        if( empty($setting['seedMoveableFlag']) ) {
                            break ;
                        }
                        $player = Yii::app()->objectLoader->load('Player', $this->playerId);
                        if( $player->gardenNum<2 ) {
                            //不足两个花园的时候 跳过消息
                            break ;
                        }
                        if( !empty($_REQUEST['native']) ) {
                            //如果是从花园进入页面 直接忽略消息
                            break ;
                        }
                        foreach( $m as $id=>$message ) {
                            $params = array(
                                'message' => $message ,
                                //'desc'    => '',
                            );
                            $result[$type][0] = $this->renderPartial('/globalMessage/NormalNotice', $params, true);
                        }
                        break ;
                    case MESSAGE_TYPE_SEED_GROWN :
                        if( empty($setting['seedGrownFlag']) ) {
                            break ;
                        }
                        if( !empty($_REQUEST['native']) ) {
                            //如果是从花园进入页面 直接忽略消息
                            break ;
                        }
                        foreach( $m as $id=>$message ) {
                            $params = array(
                                'message' => $message ,
                                //'desc'    => '',
                            );
                            $result[$type][0] = $this->renderPartial('/globalMessage/NormalNotice', $params, true);
                        }
                        break ;
                    default :
                        foreach( $m as $id=>$message ) {
                            $params = array(
                                'message' => $message ,
                                //'desc'    => '',
                            );
                            $result[$type][0] = $this->renderPartial('/globalMessage/NormalNotice', $params, true);
                        }
                }
            }
            //GuideModel::checkGuideState($this->playerId);
        }
        return $result;
    }

}
