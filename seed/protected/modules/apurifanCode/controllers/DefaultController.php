<?php
/**
 * DefaultController process the requests of famitsu code.
 *
 * Note: DefaultController extends from FamitsuController in order to get playerId, this is necessary in seven, but perhaps not in others.
 *
 * @author Qi Changhai <qi.changhai@adways.net>
 * @copyright Copyright &copy; 2003-2011 Adways Ltd.
 * @license
 */
class DefaultController extends Controller
{
    //Set layout to null to use module's layout.
    public $layout = null;

    public function filters()
    {
        return array(
            'getPlayerId',
        );
    }

    public function actionIndex()
    {
        $this->layout = "theme";

        $code = new ApurifanCode();

        if (isset($_REQUEST['code'])) {
            $user = $this->getUser();
            $code->code = $_REQUEST['code'];
            $code->userId = $user->userId;
            
            if($code->validate()){
                $user->applyCode($code);
                $this->display(); 
            } else {
                foreach ($code->getErrors() as $attribute=>$errors) {
                    //$message[] = implode("<br />", $errors);
                    //只显示一条error
                    $message[] = $errors[0];
                    break;
                }
                $this->error(implode("<br />", $message));
            }
        }

        if (isset($_REQUEST['playerId'])) {
            $this->display($this->renderPartial('index', array('playerId' => $_REQUEST['playerId']), true));
        } else {
            $this->display($this->renderPartial('index', array(), true));
        }
    }

    public function actionNone()
    {
        $this->layout = "theme";
        $this->display($this->renderPartial('none', array(), true));
    }

    protected function getUser()
    {
        $userIdName = $this->module->userIdName;
        if($this->module->userIdSource === 'session'){
            $session = Yii::app()->session;
            $session->open();
            if(isset($session[$userIdName]) && $session[$userIdName] !== ''){
                $paramUserId = $session[$userIdName];
            }else{
                throw new CException(Yii::t('ApurifanCodeModule.Exception', "Can not get userId by {userIdName} from session.", array('{userIdName}'=>$userIdName)));
            }
        }elseif($this->module->userIdSource === 'get'){
            if(isset($_GET[$userIdName]) && $_GET[$userIdName] !== ''){
                $paramUserId = $_GET[$userIdName];
            }else{
                throw new CException(Yii::t('ApurifanCodeModule.Exception', 'Can not get userId by {userIdName from $_GET.', array('{userIdName}'=>$userIdName)));
            }
        }else{
            throw new CException(Yii::t('ApurifanCodeModule.Exception', 'Do not know how to get userId'));
        }

        $userClass = $this->module->userClass;
        if(isset($userClass) && class_exists($userClass)){
            return new $userClass($paramUserId);
        }else{
            throw new CException(Yii::t('ApurifanCodeModule.Exception', 'Required class {class} not defined', array('{class}'=>$userClass)));
        }
    }

    public function actionRewards()
    {
        $this->layout = "theme";
        $user = $this->getUser();
        $this->render('rewards', array('user'=>$user));
    }
}
