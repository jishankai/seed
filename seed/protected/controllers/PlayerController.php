<?php
/**
 * PlayerController
 **/
class PlayerController extends Controller
{
    public function actionGetIndex()
    {
        $player = Yii::app()->objectLoader->load('Player', $this->playerId);
        
        $actionPoint = $player->getPlayerPoint('actionPoint');
        $actionPointValue = $actionPoint->getValue();
        $actionPointMaxValue = $actionPoint->getMax();
        $actionPointTime = $actionPoint->getRemainTime();
        
        $supplyPower = $player->getPlayerPoint('supplyPower');
        $supplyPowerValue = $supplyPower->getValue();
        $supplyPowerMaxValue = $supplyPower->getMax();
        $supplyPowerTime = $supplyPower->getRemainTime(); 
        $supplyPowerPercent = $supplyPower->getPercent();
        
        $this->display('info', array(   	
            'actionPoint' => $actionPointValue,
            'actionPointMax' => $actionPointMaxValue,
            'actionPointTime' => $actionPointTime,      
            'supplyPower' => $supplyPowerValue,
            'supplyPowerMax' => $supplyPowerMaxValue,
            'supplyPowerTime' => $supplyPowerTime,
            'supplyPowerPercent'=>$supplyPowerPercent,
        )); 
    }

    public function actionName() {
        $playerId = isset( $_REQUEST['playerId'] )?intval( $_REQUEST['playerId']) : $this->playerId ;
        
        $visualPlayerData = Util::loadConfig('VisualFriend');
        if( isset($visualPlayerData[$playerId]) ) {
            $playerName = $visualPlayerData[$playerId]['playerName'] ;
        }
        else {
            $player = Yii::app()->objectLoader->load('Player',$playerId); 
            if( !$player->isExists() ){
                $playerName = '';
            }
            else {
                $playerName = $player->playerName ;
            }
        }

        $filePath = dirname(__FILE__).'/../../images/player';
        $fontList = array(
            1 => $filePath.'/msyhbd.ttf' ,
            2 => $filePath.'/simsun.ttf' ,
        );
        $font = isset( $_REQUEST['fontId'] )&&isset($fontList[$_REQUEST['fontId']])?$fontList[$_REQUEST['fontId']]:$fontList[1];
        
        header("Content-type: image/png") ;
        $image = imagecreatefrompng($filePath.'/bg.png');
        $color = imagecolorallocate($image, 47, 113, 0);
        $borderColor = imagecolorallocate($image, 255, 255, 255);
        imagealphablending($image,true);
        imagesavealpha($image,true);
        imageantialias($image,true);
        $fontSize = 12 ;
        $x=5;$y=21;
        $borderSize = 1 ;
        imagettftext( $image,$fontSize,0,$x-$borderSize,$y,$borderColor,$font,$playerName);
        imagettftext( $image,$fontSize,0,$x+$borderSize,$y,$borderColor,$font,$playerName);
        imagettftext( $image,$fontSize,0,$x-$borderSize,$y-$borderSize,$borderColor,$font,$playerName);
        imagettftext( $image,$fontSize,0,$x+$borderSize,$y-$borderSize,$borderColor,$font,$playerName);
        imagettftext( $image,$fontSize,0,$x-$borderSize,$y+$borderSize,$borderColor,$font,$playerName);
        imagettftext( $image,$fontSize,0,$x+$borderSize,$y+$borderSize,$borderColor,$font,$playerName);
        imagettftext( $image,$fontSize,0,$x,$y-$borderSize,$borderColor,$font,$playerName);
        imagettftext( $image,$fontSize,0,$x,$y+$borderSize,$borderColor,$font,$playerName);
        imagettftext( $image,$fontSize,0,$x,$y,$color,$font,$playerName);
        imagepng($image);
        imagedestroy($image);
    }

    public function actionGameName( $seedId )  {
        $seed = Yii::app()->objectLoader->load('Seed',$seedId); 

        if( !$seed->isExists() ){
            $seedName = '';
        }
        else {
            $seedName = $seed->getName() ;
        }
        $filePath = dirname(__FILE__).'/../../images/player';
        $fontList = array(
            1 => $filePath.'/msyhbd.ttf' ,
            2 => $filePath.'/simsun.ttf' ,
        );
        $font = isset( $_REQUEST['fontId'] )&&isset($fontList[$_REQUEST['fontId']])?$fontList[$_REQUEST['fontId']]:$fontList[1];
        

        $cutLength = 15*3 ;
        $borderSize = 1 ;
        $fontSize = 12 ;
        if( mb_strlen($seedName)>$cutLength ) {
            $x1=5;$y1=22;
            $x2=5;$y2=42;
            $seedName1 = mb_substr( $seedName,0,$cutLength );
            $seedName2 = mb_substr( $seedName,$cutLength );
        }
        else {
            $x1=5;$y1=35;
            $x2=0;$y2=0;
            $seedName1 = $seedName;
            $seedName2 = false;
        }
        //var_dump($seedName1,$seedName2);exit;
        
        header("Content-type: image/png") ;
        $image = imagecreatefrompng($filePath.'/bg2.png');
        $color = imagecolorallocate($image, 236, 219, 175);
        $borderColor = imagecolorallocate($image, 0, 0, 0);
        imagealphablending($image,true);
        imagesavealpha($image,true);
        imageantialias($image,true);
        if( !empty($seedName1) ) {
            //imagettftext( $image,$fontSize,0,$x1-$borderSize,$y1,$borderColor,$font,$seedName1);
            //imagettftext( $image,$fontSize,0,$x1+$borderSize,$y1,$borderColor,$font,$seedName1);
            //imagettftext( $image,$fontSize,0,$x1-$borderSize,$y1-$borderSize,$borderColor,$font,$seedName1);
            //imagettftext( $image,$fontSize,0,$x1+$borderSize,$y1-$borderSize,$borderColor,$font,$seedName1);
            //imagettftext( $image,$fontSize,0,$x1-$borderSize,$y1+$borderSize,$borderColor,$font,$seedName1);
            imagettftext( $image,$fontSize,0,$x1+$borderSize,$y1+$borderSize,$borderColor,$font,$seedName1);
            //imagettftext( $image,$fontSize,0,$x1,$y1-$borderSize,$borderColor,$font,$seedName1);
            //imagettftext( $image,$fontSize,0,$x1,$y1+$borderSize,$borderColor,$font,$seedName1);
            imagettftext( $image,$fontSize,0,$x1,$y1,$color,$font,$seedName1);
        }

        if( !empty($seedName2) ) {
            //imagettftext( $image,$fontSize,0,$x2-$borderSize,$y2,$borderColor,$font,$seedName2);
            //imagettftext( $image,$fontSize,0,$x2+$borderSize,$y2,$borderColor,$font,$seedName2);
            //imagettftext( $image,$fontSize,0,$x2-$borderSize,$y2-$borderSize,$borderColor,$font,$seedName2);
            //imagettftext( $image,$fontSize,0,$x2+$borderSize,$y2-$borderSize,$borderColor,$font,$seedName2);
            //imagettftext( $image,$fontSize,0,$x2-$borderSize,$y2+$borderSize,$borderColor,$font,$seedName2);
            imagettftext( $image,$fontSize,0,$x2+$borderSize,$y2+$borderSize,$borderColor,$font,$seedName2);
            //imagettftext( $image,$fontSize,0,$x2,$y2-$borderSize,$borderColor,$font,$seedName2);
            //imagettftext( $image,$fontSize,0,$x2,$y2+$borderSize,$borderColor,$font,$seedName2);
            imagettftext( $image,$fontSize,0,$x2,$y2,$color,$font,$seedName2);
        }
        
        imagepng($image);
        imagedestroy($image);
    }
    
    /** 用户设置页面 **/
    public function actionSetting() {
        $setting = Yii::app()->objectLoader->load('PlayerSetting',$this->playerId);
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $params = array(
            'player'        => $player ,
            'settingData'   => $setting->getSettingData() ,
            'defaultData'   => $setting->getDefaultData() ,
        );
        $this->display( 'setting',$params );
    }

    public function actionSettingApply( $data ) {
        $settingData = (array)json_decode( urldecode($data) ) ;
        if( isset($settingData['inGame']) ){
            $settingData['inGame'] = (array)$settingData['inGame'];
        }
        if( isset($settingData['outGame']) ){
            $settingData['outGame'] = (array)$settingData['outGame'];
            foreach( $settingData['outGame'] as $key=>$val ) {
                $settingData['outGame'][$key] = (array)$val ;
            }
        }
        if( !empty($settingData) ) {
            //throw new CException( 'error data!' );
            $setting = Yii::app()->objectLoader->load('PlayerSetting',$this->playerId);
            try{
                GlobalState::set( $this->playerId,'NATIVE_ACTION','settings' );
                $setting -> saveSettingData($settingData);
            } catch ( Exception $e ) {
                throw $e ;
            }
        }
        $this->display();
    }

}
?>
