<?php 
class Notice extends CActiveRecord
{
    public function tableName()
    {
        return 'mailNotice';
    }

    /**
     * Returns the latest notice.
     * @param integer $limit the number of notice to return.
     * @return array array of notice.
     */
    public function getLatestNotice($limit=null)
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition(array(
            'deleteFlag = 0',
            'startTime < :time',
            'endTime is null OR endTime >= :time',
			'languageKey=\''.Yii::app()->language.'\'' ,
        ));
        $criteria->order = 'createTime DESC';
        if(isset($limit)){
            $criteria->limit = $limit;
        }
        $criteria->params = array(
            ':time' => time(),
        );

        return $this->findAll($criteria);
    }
    
    public function getAllEditNotice($limit=null) {
        $criteria = new CDbCriteria();
        $criteria->order = 'createTime DESC';
        if(isset($limit)){
            $criteria->limit = $limit;
        }
        return $this->findAll($criteria);
    }

    public function getNoticeById($noticeId){
        $row = $this->findByPk($noticeId);
		return $row ;
    }
    
}

?>
