<?php
/**
 * 图鉴定义信息
 * 
 * 
 */
class Catalog extends RecordModel {
    public $playerId ;
    private $configData ;
    
    /**
     * 构造函数
     * 
     * @param $playerId 
     */
    public function __construct($playerId){
        $this->playerId = $playerId ;
        $this->configData = Util::loadConfig('Catalog');
    }


    public static function attributeColumns() {
        return array('playerId','content');
    }

    /**
     * (non-PHPdoc)
     * @see protected/components/RecordModel::loadData()
     * 
     */
    protected function loadData(){
        $command = Yii::app()->db->createCommand('select * from catalog where playerId=:playerId');
        $rowData = $command->bindParam(':playerId', $this->playerId)->queryRow();
        if( empty($rowData) ) {
            $rowData = array(
                'playerId'  => $this->playerId ,
                'content'   => '' ,
            );
            self::create( $rowData );
        }
        return $rowData ;
    }
    
    /**
     * (non-PHPdoc)
     * @see protected/components/RecordModel::saveData()
     */
    protected function saveData( $attributes=array() ){
        return DbUtil::update(Yii::app()->db, 'catalog', $attributes, array('playerId'=>$this->playerId)) ;
    }
    

    public static function multiLoad( $params=array(),$isSimple=true ) { }


    public function getContentData() {
        return empty($this->content)?array():unserialize( $this->content );
    }

    public function getBodyData( $bodyId ) {
        $catalogId = $bodyId+100000 ;
        $catalogData = $this->getContentData();
        $resultData = isset( $catalogData[$catalogId] )?$catalogData[$catalogId]:array(); 
        ksort( $resultData );
        return $resultData;
    }

    public function getCatalogCount() {
        return count( $this->getContentData() );
    }

    public function saveContentData( $data=array() ) {
        $this->content = serialize( $data );
        $this->saveAttributes( array('content') );
    }

    public function getMyList() {
        foreach( $this->getContentData() as $catalogId=>$num ) {
            
        }
    }


    public function getName(){
        return Yii::t('Catalog',$this->name);
    }

    public function getDesc(){
        return Yii::t('Catalog',$this->desc);
    }

    public static function getDisplayData(){
        //
    }

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
        return DbUtil::insert(Yii::app()->db, 'catalog', $insertArr, true);
        
    }


    public function addToList( $seed ) {
        $lock = new EventLock ;
        $lockKey = 'addNewCatalog';
        if( $lock->getLock( __CLASS__,$lockKey ) ) {
            $type = empty($seed->dressId)?1:2 ;
            $dataId = CatalogData::getKey( $type,$seed->bodyId );
            $content = $this->getContentData();
            if( !empty($seed->dressId) ) {
                if( isset( $content[$dataId][$seed->budId][$seed->dressId] ) ) {
                    $content[$dataId][$seed->budId][$seed->dressId] ++ ;
                }
                else {
                    $content[$dataId][$seed->budId][$seed->dressId] = 1 ;
                }
            }
            else {
                if( isset( $content[$dataId][$seed->budId][$seed->faceId] ) ) {
                    $content[$dataId][$seed->budId][$seed->faceId] ++ ;
                }
                else {
                    $content[$dataId][$seed->budId][$seed->faceId] = 1 ;
                }
            }
            $this->saveContentData( $content );

            //成就检查
            $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_CATACOUNT, array('bodyId'=>$seed->bodyId));
            $achieveEvent->onAchieveComplete();
            //任务检查
            $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_CATAUNLOCK);
            $missionEvent->onMissionComplete();

            $lock->unlock( __CLASS__,$lockKey );
        }
    }

    public function getCount( $catalogId,$isSeedCount=false ) {
        $content = $this->getContentData();
        $number = 0 ;
        if( isset($content[$catalogId]) ){
            foreach( $content[$catalogId] as $faceList ) {
                $number += $isSeedCount?array_sum($faceList):count($faceList);
            }
            return $number ;
        }
        else {
            return 0 ;
        }
    }

    public function getBodyCount( $bodyId ) {
        static $countSum = array();
        if( !isset($countSum[$bodyId]) ) {
            $number = 0 ;
            foreach( CatalogData::getBodyAll($bodyId) as $catalogId=>$data ) {
                $number += $this->getCount( $catalogId );
            }
            $countSum[$bodyId] = $number ;
        }
        return $countSum[$bodyId];
    }

    public function getBodyCountMax( $bodyId ) {
        static $countSum = array();
        if( !isset($countSum[$bodyId]) ) {
            $number = 0 ;
            foreach( CatalogData::getBodyAll($bodyId) as $catalogId=>$data ) {
                return $number += $data->size ;
            }
            $countSum[$bodyId] = $number ;
        }
        return $countSum[$bodyId];
    }

    public function getBodyPercent( $bodyId,$isFloat=false ){
        $number = $this->getBodyCount($bodyId)/$this->getBodyCountMax($bodyId)*100;
        return $isFloat?floor($number*100)/100:floor($number);
    }

    public function getBudAll() {
        $budList = array();
        $contentData = $this->getContentData();
        foreach( $contentData as $dataId=>$x ) {
            foreach( $x as $budId=>$y ) {
                if( isset($budList[$budId]) ) {
                    $budList[$budId] ++ ;
                }
                else {
                    $budList[$budId] = 1 ;
                }
            }
        }
        return $budList;
    }

    public function getBudCountSum() {
        static $countSum = -1 ;
        if( $countSum==-1 ) {
            $countSum = count( $this->getBudAll() );
        }
        return $countSum;
    }

    public function getDressCountSum() {
        static $countSum = -1 ;
        if( $countSum==-1 ) {
            $countSum = count( $this->getDressAll( false ) );
        }
        return $countSum;
    }

    public function getBodyAll() {
        $array = array() ;
        $contentData = $this->getContentData();
        foreach( CatalogData::getAll() as $catalogId=>$data ) {
            if( empty( $contentData[$catalogId] ) ) {
                continue ;
            }
            if( isset( $array[$data->bodyId] ) ) {
                $array[$data->bodyId] ++ ;
            }
            else {
                $array[$data->bodyId] = 1 ;
            }
        }
        return $array ;
    }

    public function getBodyCountSum() {
        static $countSum = -1 ;
        if( $countSum==-1 ) {
            $countSum = count( $this->getBodyAll() );
        }
        return $countSum;
    }


    public function getDressAll( $isObject=true ) {
        $dressList = array();
        $contentData = $this->getContentData();
        foreach( $contentData as $dataId=>$x ) {
            if( intval($dataId/100000)!=2 ) continue ;
            foreach( $x as $y ) {
                foreach( $y as $dressId=>$v ) {
                    if( isset($dressList[$dressId]) ) {
                        if( $isObject ) continue ;
                        else $dressList[$dressId] ++ ;
                    }
                    else {
                        if( $isObject ) $dressList[$dressId] = Yii::app()->objectLoader->load('SeedDressData',$dressId) ;
                        else $dressList[$dressId] = 1 ;
                    }
                }
            }
        }
        return $dressList ;
    }

    public function getFaceAll() {
        $faceList = array();
        $contentData = $this->getContentData();
        foreach( $contentData as $dataId=>$x ) {
            if( intval($dataId/100000)==2 ) continue ;
            foreach( $x as $y ) {
                foreach( $y as $faceId=>$v ) {
                    if( isset($dressList[$faceId]) ) {
                        $faceList[$faceId] ++ ;
                    }
                    else {
                        $faceList[$faceId] = 1 ;
                    }
                }
            }
        }
        return $faceList ;
    }

    public function getFaceCountSum() {
        return count($this->getFaceAll()) ;
    }

    public function getPlayerData( $catalogId ) {
        $content = $this->getContentData();
        return isset($content[$catalogId])?$content[$catalogId]:array();
    }

    public function isSeedExists( $seed ) {
        return $this->checkExists( $seed->bodyId,$seed->faceId,$seed->budId );
    }

    public function checkExists( $bodyId,$faceId,$budId ) {
        return true ;
    }

    /**
     * 从图鉴中回购种子
     */
    public function buySeed( $bodyId,$faceId,$budId,$gardenId ) {
        //检查种子已经在图鉴中存在
        $this->checkExists( $bodyId,$faceId,$budId );
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $seedModel = Yii::app()->objectLoader->load('SeedModel',$this->playerId);
        //扣掉需要的游戏币
        $price = $this->getPrice( $bodyId,$faceId,$budId );
        $player->subGold( $price );
        //根据跟部分添加种子
        $newSeed = $seedModel->addSeed( $bodyId,$faceId,$budId,0,SEED_FROM_CATALOG_BUY ) ;
        $newSeed->saveLog("get","buy from catalog; Gold:-$price ;");
        //设定初始成长值
        $addGrowValue = ceil( $newSeed->getMaxGrowValue()*0.1 );
        $newSeed->setGrowValue( $addGrowValue );

        $garden = Yii::app()->objectLoader->load('Garden',$gardenId);
        $garden->checkOwner( $this->playerId );
        $gardenModel=Yii::app()->objectLoader->load('GardenModel',$this->playerId);
        $gardenModel->addSeedToGarden( $newSeed->seedId,$garden->gardenSign );
    }

    public function getPrice( $bodyId,$faceId,$budId ) {
        return  ceil
            ( 
                (
                    Yii::app()->objectLoader->load('SeedData',$bodyId)->growValue + 
                    Yii::app()->objectLoader->load('SeedData',$faceId)->growValue + 
                    Yii::app()->objectLoader->load('SeedData',$budId)->growValue
                ) * 1.2
            );
    }

    public static function getSeedLevel( $bodyId,$budId=0,$faceId=0,$dressId=0 ) {
        $level = Yii::app()->objectLoader->load('SeedData',$bodyId)->level ;
        $count = 1 ;
        if( !empty($budId) ) {
            $level += Yii::app()->objectLoader->load('SeedData',$budId)->level ;
            $count ++ ;
        }
        if( !empty($faceId) ) {
            $level += Yii::app()->objectLoader->load('SeedData',$faceId)->level ;
            $count ++ ;
        }
        if( !empty($dressId) ) {
            //$level += Yii::app()->objectLoader->load('SeedData',$dressId)->level ;
            //$count ++ ;
        }
        return max(1,floor($level/$count)) ;
    }

    public function getMaxCount( $bodyId=0,$budId=0,$faceId=0,$dressId=0 ) {
        extract( $this->configData['basic'] );

        $bodySize = count($bodyIds);
        $faceSize = count($faceIds);
        $budSize = count($budIds);
        $dressSize = count($dressIds);
        if( empty($bodyId) ) {
            return $bodySize*$budSize*($faceSize+$dressSize);
        }
        elseif( empty($budId) ) {
            return $budSize*($faceSize+$dressSize);
        }
        else {
            return $faceSize+$dressSize;
        }
    }

    public function getFaceMaxCount() {
        return count($this->configData['basic']['faceIds']);
    }

    public function getDressMaxCount() {
        return count($this->configData['basic']['dressIds']);
    }

    public function getCountSum( $bodyId=0,$budId=0,$faceId=0,$dressId=0 ) {
        $contentData = $this->getContentData();

        $sum = 0 ;
        if( empty($bodyId) ) {
            foreach( $contentData as $dataId=>$x ) {
                foreach( $x as $bId=>$y ) {
                    $sum += count($y);
                }
            }
        }
        elseif( empty($budId) ) {
            foreach( array( 100000+$bodyId,200000+$bodyId ) as $dataId ) {
                if( empty($contentData[$dataId]) ) continue ;
                foreach( $contentData[$dataId] as $y ) {
                    $sum += count($y);
                }
            }
        }
        else {
            foreach( array( 100000+$bodyId,200000+$bodyId ) as $dataId ) {
                if( empty($contentData[$dataId][$budId]) ) continue ;
                $sum += count($contentData[$dataId][$budId]);
            }
        }
        return $sum ;
    }

}
