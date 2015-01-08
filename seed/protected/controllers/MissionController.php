<?php
/**
 * MissionController
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-02-24
 * @package Seed
 **/
class MissionController extends Controller
{
    public function actionIndex()
    {
        $this->forward('list'); 
    }

    public function actionList()
    {
        $listRecords = array();
        $process = array();

        $missionRecords = MissionModel::getMissionRecords($this->playerId);
        if ($missionRecords) {
            foreach ($missionRecords as $missionRecord) {
                $process[$missionRecord->missionId] = $missionRecord->process;
                $mission = Yii::app()->objectLoader->load('Mission', $missionRecord->missionId);
                $listRecords[$missionRecord->status][] = $mission;
            }
        }
        GlobalMessage::removeMissionMessage( $this->playerId );
        //新手引导
        $guideModel = Yii::app()->objectLoader->load('GuideModel',$this->playerId);
        $isGuide = $guideModel->isCurrentGuide(20); //20 is for mission
        /*
        if( Yii::app()->objectLoader->load('GuideModel',$this->playerId)->isCurrentGuide(20) ) {
            GlobalState::set($this->playerId,'USER_GUIDE_LEVEL',21);
        }*/
        $this->display('list', array('missionList' => $listRecords, 'process' => $process, 'isGuide'=>$isGuide));
    }

    public function actionInfo()
    {
        if (isset($_REQUEST['missionId'])) {
            $missionId = $_REQUEST['missionId'];
            $id = MissionRecord::findId($this->playerId, $missionId);

            if ($id) {
                $mission = Yii::app()->objectLoader->load('Mission', $missionId);
                $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);
                if ($missionId<=10) {
                    $category = 'tutorial';
                } else if ($missionId>=1000) {
                    $category = 'random';
                } else {
                    $category = 'common';
                }
                $this->layout = "//layouts/theme";
                //新手引导
                $guideModel = Yii::app()->objectLoader->load('GuideModel',$this->playerId);
                $isGuide = $guideModel->isCurrentGuide(20); //20 is for mission
                $inGuide = $guideModel->isNewUserGuide(); //是否显示返回和关闭按钮的判断条件 
                if ($this->actionType != REQUEST_TYPE_AJAX) {
                    if ($missionRecord->status==MISSIONRECORD_COMPLETED) {
                        $this->display('complete', array('category'=>$category, 'mission'=>$mission, 'process'=>$missionRecord->processCount, 'playerId'=>$this->playerId, 'isGuide'=>$isGuide, 'inGuide'=>$inGuide));
                    } else if ($missionRecord->status==MISSIONRECORD_UNCOMPLETED) {
                        $this->display('detail', array('category'=>$category, 'mission'=>$mission, 'process'=>$missionRecord->processCount, 'playerId'=>$this->playerId, 'isGuide'=>$isGuide, 'inGuide'=>$inGuide));
                    } else if ($missionRecord->status==MISSIONRECORD_NEW) {
                        $missionRecord->viewed();
                        $this->display('detail', array('category'=>$category, 'mission'=>$mission, 'process'=>$missionRecord->processCount, 'playerId'=>$this->playerId, 'isGuide'=>$isGuide, 'inGuide'=>$inGuide));
                    } else {
                        throw new CException(Yii::t('Mission', 'mission has been confirmed or cancled'));
                    }
                } else {
                    if ($missionRecord->status==MISSIONRECORD_COMPLETED) {
                        $this->display($this->renderPartial('complete', array('category'=>$category, 'mission'=>$mission, 'process'=>$missionRecord->processCount, 'playerId'=>$this->playerId, 'isGuide'=>$isGuide, 'inGuide'=>$inGuide), true));
                    } else if ($missionRecord->status==MISSIONRECORD_UNCOMPLETED) {
                        $this->display($this->renderPartial('detail', array('category'=>$category, 'mission'=>$mission, 'process'=>$missionRecord->processCount, 'playerId'=>$this->playerId, 'isGuide'=>$isGuide, 'inGuide'=>$inGuide), true));
                    } else if ($missionRecord->status==MISSIONRECORD_NEW) {
                        $missionRecord->viewed();
                        $this->display($this->renderPartial('detail', array('category'=>$category, 'mission'=>$mission, 'process'=>$missionRecord->processCount, 'playerId'=>$this->playerId, 'isGuide'=>$isGuide, 'inGuide'=>$inGuide), true));
                    }
                }
                
               
            } else {
                throw new SException(Yii::t('Mission', 'mission was not accepted'));
            }
        }
    }

    public function actionCancel()
    {
        if (isset($_REQUEST['missionId'])) {
            $missionId = $_REQUEST['missionId'];
            $id = MissionRecord::findId($this->playerId, $missionId);

            if ($id) {
                $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);
                $missionRecord->cancel();
                $this->display(array('missionCount'=>MissionModel::recordsCount($this->playerId)));
            } else {
                throw new CHttpException(400, 'Illegal Arguments');
            }
        }
    }

    public function actionConfirm()
    {
        $this->showGlobalMessage = false;
        if (isset($_REQUEST['missionId'])) {
            $missionId = $_REQUEST['missionId'];
            $id = MissionRecord::findId($this->playerId, $missionId);

            if ($id) {
                $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);
                $missionRecord->confirm();
                $this->display(array('missionCount'=>MissionModel::recordsCount($this->playerId)));
            } else {
                throw new CHttpException(400, 'Illegal Arguments');
            }
        }
    }

    public function actionTorturalConfirm()
    {
        $this->showGlobalMessage = false;
        if (isset($_REQUEST['missionId'])) {
            $missionId = $_REQUEST['missionId'];
            $id = MissionRecord::findId($this->playerId, $missionId);


            if ($id) {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);
                    $missionRecord->confirm();

                    $listRecords = array();
                    $process = array();

                    $missionRecords = MissionModel::getMissionRecords($this->playerId);
                    if ($missionRecords) {
                        foreach ($missionRecords as $missionRecord) {
                            $process[$missionRecord->missionId] = $missionRecord->process;
                            $mission = Yii::app()->objectLoader->load('Mission', $missionRecord->missionId);
                            $listRecords[$missionRecord->status][] = $mission;
                        }
                    }
                    //新手引导
                    Yii::app()->objectLoader->load('GuideModel',$this->playerId)->checkMissionState($missionId);
                    //新手引导成就检查
                    if ($missionId==9) {
                        $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_TUTORIAL);
                        $achieveEvent->onAchieveComplete();
                    }

                    GlobalMessage::removeMissionMessage( $this->playerId );
                    GuideModel::checkGuideState( $this->playerId );
                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollback();
                    throw $e;
                }

                $this->display(array('missionId'=>$missionRecord->missionId));
            } else {
                throw new CHttpException(400, 'Illegal Arguments');
            }
        }
    }

    public function actionGet()
    {
        $player = Yii::app()->objectLoader->load('Player', $this->playerId);
        if ($player->getStatus('guideLevel')>=124) {
            MissionRecord::initNew($this->playerId);
        }
        $this->display();
    }

}
?>
