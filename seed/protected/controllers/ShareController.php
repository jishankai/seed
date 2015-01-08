<?php

class ShareController extends Controller {

    //首次打开分享窗口
    public function actionPhotoShow() {
        $stateInfo = $this->getSnsContent();

        $this->display('photoShow', array(
            'isTwitterShare' => $stateInfo['isTwitterShare'],
            'isFacebookShare' => $stateInfo['isFacebookShare'],
            'shareMessage' => $stateInfo['shareMessage'],
            'shareImageName' => $stateInfo['shareImageName'],
            'isTwitterBing' => SnsModel::checkTwitterBing(),
            'isFacebookBing' => SnsModel::checkFacebookBing(),
        ));
    }

    public function actionSetValidate() {
        $data = array(
            'isTwitterBing' => SnsModel::checkTwitterBing(),
            'isFacebookBing' => SnsModel::checkFacebookBing(),
            'twitterLoginUrl' => base64_encode('http://' . $_SERVER['HTTP_HOST'] . $this->createUrl('/sns/twitterBing&fromAction=upload')),
            'facebookLoginUrl' => base64_encode('http://' . $_SERVER['HTTP_HOST'] . $this->createUrl('/sns/FacebookBing&fromAction=upload')),
        );
        $this->display($data);
    }

    public function actionMessageShow() {
        $player = Yii::app()->objectLoader->load('Player', $this->playerId);
        $stateInfo = array();
        $stateInfo['isTwitterShare'] = 0;
        $stateInfo['isFacebookShare'] = 0;
        $stateInfo['shareMessage'] = Yii::t('View', 'Together with me to play seed,I reach') . $player->level . Yii::t('View', 'level!');
        $stateInfo['shareImageName'] = '';
        $this->setSnsContent($stateInfo);
        $this->actionPhotoShow();
    }

    public function actionCheckBing() {
        $this->display('photoShow', array(
            'isTwitterBing' => SnsModel::checkTwitterBing(),
            'isFacebookBing' => SnsModel::checkFacebookBing(),
        ));
    }

    //上传图片
    public function actionUploadFile() {
        if ($_FILES["file"]["error"] > 0) {
            $code = $_FILES["file"]["error"];
        } else {
            $upload = $_FILES["file"]["name"];
            $type = $_FILES["file"]["type"];
            $size = ($_FILES["file"]["size"] / 1024) . " Kb";
            $tempfile = $_FILES["file"]["tmp_name"];

            $randFileName = $this->getFileName($_FILES["file"]["name"]) . '_' . time() . '_' . rand(1000, 9999);

            $imageName = $randFileName . '.' . $this->getFileExtension($_FILES["file"]["name"]);

            $imagePath = dirname(__FILE__) . Yii::app()->params['uploadPath'] . $imageName;

            $smallImagePath = dirname(__FILE__) . Yii::app()->params['uploadPath'] . 'small_' . $imageName;

            $middleImagePath = dirname(__FILE__) . Yii::app()->params['uploadPath'] . 'middle_' . $imageName;

            move_uploaded_file($_FILES["file"]["tmp_name"], $imagePath);

            $message = "Stored in: " . dirname(__FILE__) . Yii::app()->params['uploadPath'] . $_FILES["file"]["name"];

            $img = GetImageSize($imagePath);
            $im = ImageCreateFromPNG($imagePath);
            $small_img = imagecreatetruecolor(50, 40);
            ImageAlphaBlending($small_img, false);
            ImageSaveAlpha($small_img, true);
            imagecopyresized($small_img, $im, 0, 0, 0, 0, 50, 40, $img[0], $img[1]);
            imagepng($small_img, $smallImagePath);

            $img2 = GetImageSize($imagePath);
            $im2 = ImageCreateFromPNG($imagePath);
            $middle_img = imagecreatetruecolor(800, 600);
            ImageAlphaBlending($middle_img, false);
            ImageSaveAlpha($middle_img, true);
            imagecopyresized($middle_img, $im2, 0, 0, 0, 0, 800, 600, $img2[0], $img2[1]);
            imagepng($middle_img, $middleImagePath);

            $stateInfo = array();
            $stateInfo['isTwitterShare'] = 0;
            $stateInfo['isFacebookShare'] = 0;
            $stateInfo['shareMessage'] = Yii::t('View', 'See my garden! A lot of small seeds live here!');
            $stateInfo['shareImageName'] = $randFileName;

            $this->setSnsContent($stateInfo);

            $this->display(array());
        }
    }

    //执行分享图片
    public function actionShareImage() {
        $isTwitterShare = 0;
        $isFacebookShare = 0;
        $shareMessage = '';
        $shareImageName = '';
        if (!(isset($_REQUEST['isTwitterShare']))) {
            $this->error('isTwitterShare is null');
        } else {
            $isTwitterShare = $_REQUEST['isTwitterShare'];
        }
        if (!(isset($_REQUEST['isFacebookShare']))) {
            $this->error('isFacebookShare is null');
        } else {
            $isFacebookShare = $_REQUEST['isFacebookShare'];
        }
        if (!(isset($_REQUEST['shareMessage']))) {
            $this->error('shareMessage is null');
        } else {
            $shareMessage = $_REQUEST['shareMessage'];
        }
        if (!(isset($_REQUEST['shareImageName']))) {
            $this->error('shareImageName is null');
        } else {
            $shareImageName = $_REQUEST['shareImageName'];
        }

        $stateInfo = array();
        $stateInfo['isTwitterShare'] = $isTwitterShare;
        $stateInfo['isFacebookShare'] = $isFacebookShare;
        $stateInfo['shareMessage'] = $shareMessage;
//        $stateInfo['shareImageName'] = $shareImageName;

        $this->setSnsContent($stateInfo);

        if ($isTwitterShare == 1) {
            $twitterResult = $this->twitterUpload($shareImageName, $shareMessage);
        }
        if ($isFacebookShare == 1) {
            $facebookResult = $this->facebookUpload($shareImageName, $shareMessage);
        }

        $this->finallyDo();

        $params = array(
            'twitterResult' => isset($twitterResult) ? $twitterResult : '',
            'facebookResult' => isset($facebookResult) ? $facebookResult : '',
        );

        $this->display($params);
    }

    //上传完成以后的处理工作
    public function finallyDo() {
        $stateInfo = $this->getSnsContent();

        if (!empty($stateInfo)) {
            if ($stateInfo['isTwitterShare'] == 0 || $stateInfo['isTwitterShare'] == 0) {
                if ($stateInfo['shareImageName'] != '') {
                    $imagePath = dirname(__FILE__) . Yii::app()->params['uploadPath'] . $stateInfo['shareImageName'] . '.png';
                    $smallImagePath = dirname(__FILE__) . Yii::app()->params['uploadPath'] . 'small_' . $stateInfo['shareImageName'] . '.png';
                    $middleImagePath = dirname(__FILE__) . Yii::app()->params['uploadPath'] . 'middle_' . $stateInfo['shareImageName'] . '.png';

                    $this->delfile($imagePath);
                    $this->delfile($smallImagePath);
                    $this->delfile($middleImagePath);
                }
            }
        }

        $stateInfo = array();
        $stateInfo['isTwitterShare'] = 0;
        $stateInfo['isFacebookShare'] = 0;
        $stateInfo['shareMessage'] = '';
        $stateInfo['shareImageName'] = '';

        $this->setSnsContent($stateInfo);
    }

    //执行Twitter上传
    public function twitterUpload($shareImageName, $shareMessage) {
        require_once dirname(__FILE__) . Yii::app()->params['twitter']['extFile1'];
        require_once dirname(__FILE__) . Yii::app()->params['twitter']['extFile2'];

        $session = Yii::app()->session;
        $session->open();
        $userId = $session['userId'];
        $userModel = new UserModel('UserTwitter');

        $tmhOAuth = new tmhOAuth(array(
                    'consumer_key' => Yii::app()->params['twitter']['consumer_key'],
                    'consumer_secret' => Yii::app()->params['twitter']['consumer_secret'],
                ));
        $tokenArray = $userModel->getTwitterTokenDB($userId);
        if (!empty($tokenArray)) {
            $tmhOAuth->config['user_token'] = $tokenArray['userToken'];
            $tmhOAuth->config['user_secret'] = $tokenArray['userSecret'];
        }
        $code = $tmhOAuth->request('GET', $tmhOAuth->url('1/account/verify_credentials'));
        if ($code != 200) {
            return false;
        } else {
            if ($shareImageName == '') {
                $code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
                    'status' => $shareMessage,
                        )
                );
            } else {
                $image = dirname(__FILE__) . Yii::app()->params['uploadPath'] . $shareImageName;
                $code = $tmhOAuth->request(
                        'POST', 'https://upload.twitter.com/1/statuses/update_with_media.json', array(
                    'media[]' => "@{$image};type=image/png;filename={$image}",
                    'status' => $shareMessage,
                        ), true, // use auth
                        true  // multipart
                );
            }
//            $twitter_log = dirname(__FILE__) . Yii::app()->params['uploadPath'] . 'twitter_log.log';
            if ($code == 200) {
//                tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
//                $this->writefile($tmhOAuth->response['response'], $twitter_log);
                $stateInfo = array();
                $stateInfo['isTwitterShare'] = 0;
                $this->setSnsContent($stateInfo);
                return true;
            } else {
//                $twitter_error_log = dirname(__FILE__) . Yii::app()->params['uploadPath'] . 'twitter_error_log.log';
//                $this->writefile($tmhOAuth->response['response'], $twitter_error_log);
//                tmhUtilities::pr(htmlentities($tmhOAuth->response['response']));
                return false;
            }
        }
    }

    //执行Fackbook上传
    public function facebookUpload($shareImageName, $shareMessage) {
        require_once(dirname(__FILE__) . Yii::app()->params['facebook']['extFile']);

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
        $access_token = trim($userModel->getFaceTokenDB($userId));
        $facebook->setAccessToken($access_token);
        $user_id = $facebook->getUser();
        if ($user_id) {
            try {
                if ($shareImageName == '') {
                    $ret_obj = $facebook->api('/me/feed', 'POST', array(
                        // 'link' =>$link,
                        'message' => $shareMessage
                            ));
                } else {
                    $photo = dirname(__FILE__) . Yii::app()->params['uploadPath'] . $shareImageName;
//                    $album_id = 0;
//                    $albums = $facebook->api('/me/albums');
//                    foreach ($albums["data"] as $album) {
//                        //Test if the current album name is the one that has just been created
//                        if ($album["name"] == Yii::t('View', 'AlbumName')) {
//                            $album_id = $album["id"];
//                            break;
//                        }
//                    }
//                    if ($album_id == 0) {
//                        $post_data = array(
//                            'name' => Yii::t('View', 'AlbumName'),
//                            'description' => Yii::t('View', 'AlbumName')
//                        );
//                        $data['album'] = $facebook->api("/me/albums", 'post', $post_data);
//                        $album_id = $data['album']['id'];
//                    }
                    //$file = $file_name;
                    $post_data = array(
                        "message" => $shareMessage,
                        "source" => '@' . $photo
                    );
                    $data['photo'] = $facebook->api("/me/photos", 'post', $post_data);
                }
                $stateInfo = array();
                $stateInfo['isTwitterShare'] = 0;
                $this->setSnsContent($stateInfo);
                return true;
            } catch (FacebookApiException $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    //获取分享信息
    public function getSnsContent() {
        $snsModel = Yii::app()->objectLoader->load('SnsModel', $this->playerId);
        return $stateInfo = $snsModel->getStateInfo();
    }

    //设置分享信息
    public function setSnsContent($array) {
        $snsModel = Yii::app()->objectLoader->load('SnsModel', $this->playerId);
        $stateInfo = $snsModel->getStateInfo();

        if (isset($array['isTwitterShare']))
            $stateInfo['isTwitterShare'] = $array['isTwitterShare'];
        if (isset($array['isFacebookShare']))
            $stateInfo['isFacebookShare'] = $array['isFacebookShare'];
        if (isset($array['shareMessage']))
            $stateInfo['shareMessage'] = $array['shareMessage'];
        if (isset($array['shareImageName']))
            $stateInfo['shareImageName'] = $array['shareImageName'];

        $snsModel->setStateInfo($stateInfo);
    }

    //获取文件名
    function getFileName($filepath) {
        $fext = array_pop(explode(".", $filepath));
        $fname = basename($filepath, "." . $fext);
        return $fname;
    }

    //获取文件后缀名
    function getFileExtension($filepath) {
        $filearr = explode(".", $filepath);
        $filetype = end($filearr);
        return $filetype;
    }

    //写文件
    function writefile($body, $path) {
        $this->createDir(dirname($path));
        $handle = fopen($path, 'w');
        fwrite($handle, $body);
        fclose($handle);
        return 1;
    }

    //创建文件夹
    function createDir($path) {
        if (!file_exists($path)) {
            $this->createDir(dirname($path));
            //mkdir($path, 0777);
        }
    }

    //删除文件
    function delfile($path) {
        if (file_exists($path)) {
            if (!unlink($path)) {
                //echo ("Error deleting $path");
            } else {
                //echo ("Deleted $path");
            }
        }
    }

    //编码
    function safeEncoding($string, $outEncoding = 'UTF-8') {
        $encoding = "UTF-8";
        for ($i = 0; $i < strlen($string); $i++) {
            if (ord($string{$i}) < 128)
                continue;
            if ((ord($string{$i}) & 224) == 224) {
                //第一个字节判断通过     
                $char = $string{++$i};
                if ((ord($char) & 128) == 128) {
                    //第二个字节判断通过     
                    $char = $string{++$i};
                    if ((ord($char) & 128) == 128) {
                        $encoding = "UTF-8";
                        break;
                    }
                }
            }
            if ((ord($string{$i}) & 192) == 192) {
                //第一个字节判断通过     
                $char = $string{++$i};
                if ((ord($char) & 128) == 128) {
                    //第二个字节判断通过     
                    $encoding = "GB2312";
                    break;
                }
            }
        }

        if (strtoupper($encoding) == strtoupper($outEncoding))
            return $string;
        else
            return iconv($encoding, $outEncoding, $string);
    }

    //错误输出
    function outputError($tmhOAuth) {
        echo 'Error: ' . $tmhOAuth->response['response'] . PHP_EOL;
        tmhUtilities::pr($tmhOAuth);
    }

}