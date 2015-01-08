<?php
/**
 * VisualFriend Controller
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-05-11
 * @package Seed
 **/
class VisualFriendController extends Controller
{
    public function actionFosterSeedInfo()
    {
        if (isset($_REQUEST['id'])&&isset($_REQUEST['gardenId'])) {
            $id = $_REQUEST['id'];
            $gardenId = $_REQUEST['gardenId']; 

            $transaction = Yii::app()->db->beginTransaction();
            try {
                //读取虚拟好友的种子对象
                $key = VisualFriend::createKey($id, $this->playerId);
                $visualFriend = Yii::app()->objectLoader->load('VisualFriend', $key);
                $isCharged = VisualFriend::isChargedToday($this->playerId)?1:0;
                $fosterSeed = $visualFriend->getFosterSeed();

                //任务检查
                $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_VISIT);
                $missionEvent->onMissionComplete();

                //新手引导状态
                if( Yii::app()->objectLoader->load('GuideModel',$this->playerId)->isCurrentGuide(100) ) {
                    GlobalState::set( $this->playerId,'USER_GUIDE_LEVEL',100 );
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                throw $e;
            }

            if ($fosterSeed) {
                if ($fosterSeed->gardenId==$gardenId) {
                    $seedInfo = $fosterSeed->getNativeData();
                    $this->display(array($fosterSeed->seedId=>$seedInfo, 'isCharged'=>$isCharged), array('callback'=>array('fosterState'=>1)));
                } else {
                    $this->display(array('isCharged'=>$isCharged), array('callback'=>array('fosterState'=>2)));
                }
            } else {
                $fosterSeedNum = Seed::FosterSeedNum($this->playerId);
                $player = Yii::app()->objectLoader->load('Player',$this->playerId);
                if ($fosterSeedNum >= min(max(1,intval($player->level/10)),9) ){
                    $fosterState = 2;
                }
                else {
                    $fosterState = 0;
                }

                $this->display(array('isCharged'=>$isCharged), array('callback'=>array('fosterState'=>$fosterState)));
            }


        } else {
            throw new CException(400, 'Illegal Arguments');
        }
    }

    /*
    //生成配置文件脚本
    public function actionCreateVisualGardenForNative()
    {
        set_time_limit(12000);
        $command = Yii::app()->db->createCommand('SELECT gardenId FROM garden WHERE playerId in (1670) ORDER BY playerId ASC;');
        $gardenIds = $command->queryColumn();
        foreach ($gardenIds as $gardenId) {
            $url = $this->createAbsoluteUrl('garden/visualDecoInfo', array('gardenId'=>$gardenId, 'isApi'=>1));
            $contents = file_get_contents($url);
            file_put_contents("VisualGarden".$gardenId.".txt", $contents, FILE_APPEND); 
            echo $url."\n";
        }

      //sleep(1);
        $this->display();
    }

    public function actionCreateVisualSeed()
    {
        $command = Yii::app()->db->createCommand('SELECT * FROM seed WHERE playerId in (1670) ORDER BY playerId ASC;');
        $visualSeeds = $command->queryAll();
        $contents = "<?php\nreturn array(\n";
        $banList = array('createTime', 'updateTime');
        foreach ($visualSeeds as $visualSeed) {
            $contents = $contents.$visualSeed['seedId']."=>array(\n";
            foreach ($visualSeed as $key=>$value) {
                if (!in_array($key, $banList)) {
                    $contents = $contents."'".$key."'=>'".$value."',\n";
                }
            }
            $contents = $contents."),\n";
        }
        $contents = $contents.");\n?>";
        file_put_contents("VisualSeeds.cfg.php", $contents, FILE_APPEND); 
        $this->display();
    }

    public function actionCreateVisualGardenForWeb()
    {
        $command = Yii::app()->db->createCommand('SELECT * FROM garden WHERE playerId in (1670) ORDER BY playerId ASC;');
        $visualGardens = $command->queryAll();
        $contents = "<?php\nreturn array(\n";
        $banList = array('seedCount', 'decorationInfo', 'createTime', 'updateTime');
        foreach ($visualGardens as $visualGarden) {
            $contents = $contents.$visualGarden['gardenId']."=>array(\n";
            foreach ($visualGarden as $key=>$value) {
                if (!in_array($key, $banList)) {
                    $contents = $contents."'".$key."'=>'".$value."',\n";
                }
            }
            $contents = $contents."),\n";
        }
        $contents = $contents.");\n?>";
        file_put_contents("VisualGardens.cfg.php", $contents, FILE_APPEND);
        $this->display();
    }
     */
}
?>
