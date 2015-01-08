<?php
/**
 * 种子基础类
 * 
 * 
 */
class PushQueue extends RecordModel {
    public $queueId ; 
    private $event ;
    
    public function __construct($queueId){
        $this->queueId = $queueId ;
        $this->event = new EventLock ;
    }
    
    /**
     * 可以访问的种子属性字段
     * 
     */
    public static function attributeColumns(){
        return array(
                'playerId','content','state','sendTime','createTime',
            );
    }
    
    protected function loadData(){
        $command = Yii::app()->db->createCommand('select * from pushQueue where queueId=:queueId');
        $rowData = $command->bindParam(':queueId', $this->queueId)->queryRow();
        return $rowData ;
    }
    
    protected function saveData( $attributes=array() ){
        return DbUtil::update(Yii::app()->db, 'pushQueue', $attributes, array('queueId'=>$this->queueId)) ;
    }

    public function getLock() {
        return $this->event->getLock( __CLASS__,$this->queueId );
    }
    
    public function setState() {
        $this->state = 1 ;
        $this->saveAttributes( array('state') );
        $this->releaseLock();
    }

    public function releaseLock() {
        return $this->event->unLock( __CLASS__,$this->queueId );
    }
    
    public static function multiLoad( $params=array(),$isSimple=true ){  }
    
    public static function create( $createInfo ) {
        $insertArr = array();
            foreach(self::attributeColumns() as $key){
            if(isset($createInfo[$key])){
                $insertArr[$key] = $createInfo[$key];
            }
            else {
                $insertArr[$key] = 0 ;
            }
        }
        $insertArr['createTime'] = time();
        return DbUtil::insert(Yii::app()->db, 'pushQueue', $insertArr, true);
    }
    
    public static function getDealQueue( $limit=10 ) {
        $currentTime = time();
        $sql = "select * from pushQueue where state=0 and sendTime<=$currentTime order by sendTime asc limit $limit";
        $command = Yii::app()->db->createCommand( $sql );
        $data = array();
        foreach( $command->queryAll() as $row ){
            $data[$row['queueId']] = new PushQueue($row['queueId']);
            $data[$row['queueId']]->loadData($row);
        }

        return $data ;
    }
    
}