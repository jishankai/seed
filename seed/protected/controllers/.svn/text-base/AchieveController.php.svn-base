<?php
/**
 * AchieveController
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-01
 * @package Seed
 **/

class AchieveController extends Controller
{
    public function actionIndex()
    {
        $this->forward('collectList');
    }

    public function actionCollectList()
    {
        self::listByCategory(ACHIEVEMENT_CATE_COLLECT);
    }

    public function actionConsumeList()
    {
        self::listByCategory(ACHIEVEMENT_CATE_CONSUME);
    }

    public function actionTaskList()
    {
        self::listByCategory(ACHIEVEMENT_CATE_TASK);
    }

    public function actionOtherList()
    {
        self::listByCategory(ACHIEVEMENT_CATE_OTHER);
    }

    public function listByCategory($category)
    {
        $listRecords = array();
        $process = array(); //统计完成度
        $records = array(); //筛选同类别成就
        $achievementRecords = AchievementModel::getAchievementRecords($this->playerId);
        $achievementRecords = array_reverse($achievementRecords);
        if ($achievementRecords) {
            foreach ($achievementRecords as $achievementRecord) {
                if (Achievement::getCategoryById($achievementRecord->achievementId) == $category) {
                    $achievement = Yii::app()->objectLoader->load('Achievement', $achievementRecord->achievementId);
                    if (!empty($records[$achievement->class])) {
                        if ($achievementRecord->status!=ACHIEVEMENTRECORD_COMPLETED && $achievement->paramsCount<$records[$achievement->class]->paramsCount) {
                            $records[$achievement->class] = $achievement;
                        }
                    } else {
                        $records[$achievement->class] = $achievement;
                    }
                }    
            }
        }
        foreach ($records as $record) {
            $id  = AchievementRecord::findId($this->playerId, $record->achievementId);
            $achievementRecord = Yii::app()->objectLoader->load('AchievementRecord', $id);
            $process[$record->achievementId] = $achievementRecord->processCount;
            $listRecords[$achievementRecord->status][] = $record;
        }
        $achievements = AchievementModel::getUncheckAchievementByCategory($this->playerId, $category);
        if (isset($listRecords[ACHIEVEMENTRECORD_UNCOMPLETED])) {
            $listRecords[ACHIEVEMENTRECORD_UNCOMPLETED] = array_merge($listRecords[ACHIEVEMENTRECORD_UNCOMPLETED], $achievements);
        } else {
            $listRecords[ACHIEVEMENTRECORD_UNCOMPLETED] = $achievements;
        }
        $listRecords[ACHIEVEMENTRECORD_UNCOMPLETED] = array_reverse($listRecords[ACHIEVEMENTRECORD_UNCOMPLETED]);
        $completed = AchievementRecord::getCompletedCount($this->playerId);
        $total = AchievementRecord::getTotalCount($this->playerId);
        $this->layout = "//layouts/theme";
        if ($this->actionType != REQUEST_TYPE_AJAX) {
            $this->display('index', array('completed' => $completed, 'total' => $total, 'category' => $category, 'achievementList' => $listRecords, 'process' => $process));
        } else {
            $this->display(array('html'=>$this->renderPartial('index', array('completed' => $completed, 'total' => $total, 'category' => $category, 'achievementList' => $listRecords, 'process' => $process),true), 'countSum'=>count($listRecords, 1)-count($listRecords)));
        }
    }
}
?>
