<?php

class SnsController extends Controller {

    public function actionTwitterBing() {
        require_once dirname(__FILE__) . Yii::app()->params['twitter']['extFile1'];
        require_once dirname(__FILE__) . Yii::app()->params['twitter']['extFile2'];

        $new_user_token = '';
        $new_user_secret = '';
        $tokenArray = array();

        $session = Yii::app()->session;
        $session->open();
        $userId = $session['userId'];
        $userModel = new UserModel('UserTwitter');

        $tmhOAuth = new tmhOAuth(array(
                    'consumer_key' => Yii::app()->params['twitter']['consumer_key'],
                    'consumer_secret' => Yii::app()->params['twitter']['consumer_secret'],
                ));

        if (isset($_REQUEST['oauth_verifier'])) {
            $tmhOAuth->config['user_token'] = $_SESSION['oauth']['oauth_token'];
            $tmhOAuth->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];

            $code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/access_token', ''), array(
                'oauth_verifier' => $_REQUEST['oauth_verifier']
                    ));

            if ($code == 200) {
                $_SESSION['access_token'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
                unset($_SESSION['oauth']);
            } else {
                //outputError($tmhOAuth);
            }
            $new_user_token = $_SESSION['access_token']['oauth_token'];
            $new_user_secret = $_SESSION['access_token']['oauth_token_secret'];
        }

        if ($new_user_token != '' && $new_user_secret != '') {
            $tokenArray = $userModel->setTwitterTokenDB($userId, $new_user_token, $new_user_secret);
            $tmhOAuth->config['user_token'] = $new_user_token;
            $tmhOAuth->config['user_secret'] = $new_user_secret;
        }

        $code = $tmhOAuth->request('GET', $tmhOAuth->url('1/account/verify_credentials'));

        if ($code != 200) {

            $tmhOAuth = null;

            $tmhOAuth = new tmhOAuth(array(
                        'consumer_key' => Yii::app()->params['twitter']['consumer_key'],
                        'consumer_secret' => Yii::app()->params['twitter']['consumer_secret'],
                    ));

            $here = tmhUtilities::php_self();

            $F_URL = 'http://' . $_SERVER['HTTP_HOST'];
            $callback = $F_URL . $this->createUrl('/sns/twitterBing');

            $params = array(
                'oauth_callback' => $callback
            );

            $code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/request_token', ''), $params);

            if ($code == 200) {
                $_SESSION['oauth'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
                $method = 'authenticate';
                $force = '';
                $authurl = $tmhOAuth->url("oauth/{$method}", '') . "?oauth_token={$_SESSION['oauth']['oauth_token']}{$force}";
                $this->redirect($authurl);
            } else {
                //outputError($tmhOAuth);
//                $this->display('bingView', array(
//                    'message' => Yii::t('View', 'bing fail？'),
//                ));
//                echo 'TWITTER BING FAIL!';
                echo '<html><head><meta charset="utf-8"/><meta http-equiv="Refresh" content="2; url=hkadwaysseed://"/></head><body><h1 style="color:blue">' . Yii::t('View', 'bing fail') . '</h1></body></html>';
            }
        } else {
//            $this->display('bingView', array(
//                'message' => Yii::t('View', 'bing ok？'),
//            ));
//            echo 'TWITTER BING OK!';
              $this->redirect('hkadwaysseed://'); 
        }
    }

    public function actionFacebookBing() {
        require_once(dirname(__FILE__) . Yii::app()->params['facebook']['extFile']);

        $new_access_token = '';
        $access_token = '';

        $session = Yii::app()->session;
        $session->open();
        $userId = $session['userId'];
        $userModel = new UserModel('UserTwitter');

        $config = array(
            'appId' => Yii::app()->params['facebook']['appId'],
            'secret' => Yii::app()->params['facebook']['secret'],
            'fileUpload' => true,
        );
        $facebook = new Facebook($config);

        $user_id = $facebook->getUser();
        if ($user_id && $user_id != $config['appId'] . '|' . $config['secret']) {
            $new_access_token = $facebook->getAccessToken();
            $userModel->setFaceTokenDB($userId, $new_access_token);
        }

        if ($new_access_token != '') {
            $access_token = $new_access_token;
        } else {
            $access_token = trim($userModel->getFaceTokenDB($userId));
        }

        $facebook = new Facebook($config);
        $facebook->setAccessToken($access_token);
        $user_id = $facebook->getUser();

        if ($user_id) {
//            $this->display('bingView', array(
//                'message' => Yii::t('View', 'bing ok？'),
//            ));
//            echo 'FACEBOOK BING OK!';
            $this->redirect('hkadwaysseed://');
        } else {
            $login_url = $facebook->getLoginUrl(array(
                'display' => 'touch',
                'scope' => 'photo_upload',
                    ));
            $this->redirect($login_url);
        }
    }

    function outputError($tmhOAuth) {
        echo 'Error: ' . $tmhOAuth->response['response'] . PHP_EOL;
        tmhUtilities::pr($tmhOAuth);
    }

}