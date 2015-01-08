<?php

class SeedDisplayData extends CBehavior {

    public function getDisplayData( $isJson=true,$index=0 ) {
        $this->getOwner()->getGrowValue();
        $data = array(
            1 => Yii::app()->objectLoader->load('SeedData',$this->getOwner()->bodyId)->getDisplayData($index) , 
            2 => Yii::app()->objectLoader->load('SeedData',$this->getOwner()->faceId)->getDisplayData($index) , 
            3 => Yii::app()->objectLoader->load('SeedData',$this->getOwner()->budId)->getDisplayData($index) , 
        );
        $result = array();
        if( $this->getOwner()->growPeriod >= SEED_GROW_PERIOD_EQUIPPED&&!empty($this->getOwner()->dressId) ) {
            $step = 4 ;
            $data[4] = Yii::app()->objectLoader->load('SeedData',$this->getOwner()->dressId)->getDisplayData($index) ;
        }
        elseif( $this->getOwner()->growPeriod>=SEED_GROW_PERIOD_GROWN ) {
            $step = 3 ;
        }
        elseif( $this->getOwner()->growPeriod>=SEED_GROW_PERIOD_GROWING ) {
            $step = 2 ;
        }
        else {
            $step = 1 ;
            $data[0] = Yii::app()->objectLoader->load('SeedData',2031)->getDisplayData($index) ;
        }
        
        foreach( $data as $index=>$block ) {
            if( $index>$step ) continue ;
            $result[] = $block ;
        }

        return $isJson?json_encode( $result ):$result;
    }

    public function getAnimationData( $isJson=false ) {
        $array = array();
        for( $i=0;$i<10;$i++ ) {
            $array[$i] = $this->getDisplayData(false,$i);
        } 
        return $isJson?json_encode($array):$array ;
    }


    public function getNativeParts() {
        $this->getOwner()->getGrowValue();
        $data = array(
            4 => Yii::app()->objectLoader->load('SeedData',$this->getOwner()->budId)->dataName , 
            3 => Yii::app()->objectLoader->load('SeedData',$this->getOwner()->faceId)->dataName , 
            2 => Yii::app()->objectLoader->load('SeedData',$this->getOwner()->bodyId)->dataName , 
        );
        $result = array();
        if( $this->getOwner()->growPeriod >= SEED_GROW_PERIOD_EQUIPPED&&!empty($this->getOwner()->dressId) ) {
            $step = 5 ;
            $dressData = Yii::app()->objectLoader->load('SeedDressData',$this->getOwner()->dressId) ;
            $zIndex = $dressData->position==1?5:1 ;
            $data[$zIndex] = $dressData->dataName ;
        }
        elseif( $this->getOwner()->growPeriod>=SEED_GROW_PERIOD_GROWN ) {
            $step = 4 ;
        }
        elseif( $this->getOwner()->growPeriod>=SEED_GROW_PERIOD_GROWING ) {
            $step = 3 ;
        }
        else {
            $step = 2 ;
            $data[0] = Yii::app()->objectLoader->load('SeedData',2031)->dataName ;
        }

        $result = array();
        $orderArray = array( 4,5,0,3,2,1 );
        foreach( $orderArray as $k ) {
            if( $k>$step||!isset($data[$k]) ) continue ;
            $result[] = $data[$k] ;
        }

        return $result ;
    }

    public function getNativeData() {
        $this->getOwner()->getGrowValue();
        $result = array(
            'seedId'    => $this->getOwner()->seedId ,
            'parts'     => $this->getNativeParts() ,
            'isFoster'  => $this->getOwner()->isFoster==1?1:0 ,
            'showInfo'  => 1 ,
            'showFeed'  => $this->getOwner()->feedCount>0&&$this->getOwner()->isFoster!=1?1:0 ,
            'showBreed' => $this->getOwner()->feedCount>0&&$this->getOwner()->isFoster!=1?1:0 ,
            'showGame'  => $this->getOwner()->growPeriod>=SEED_GROW_PERIOD_GROWING&&$this->getOwner()->isFoster!=1?1:0 ,
            'showMove'  => $this->getOwner()->growPeriod>=SEED_GROW_PERIOD_GROWING&&$this->getOwner()->isFoster!=1&&Yii::app()->objectLoader->load('Player',$this->getOwner()->playerId)->gardenNum>1?1:0 ,
            'nextGrow'  => -1 ,
            'growPeriod'=> $this->getOwner()->growPeriod ,
            'gardenId'  => $this->getOwner()->gardenId ,
        );
        if( $this->getOwner()->growPeriod<SEED_GROW_PERIOD_GROWING ) {
            $result['nextGrow'] = $this->getOwner()->getGrowLimitSecond(SEED_GROW_PERIOD_GROWING);
        }
        elseif( $this->getOwner()->growPeriod<SEED_GROW_PERIOD_GROWN ){
            $result['nextGrow'] = $this->getOwner()->getGrowLimitSecond();
        }
        else {
            $result['nextGrow'] = -1;
        }
        return $result ;
    }
    

    public function getGrowValueData( $growPeriod=5 ) {
        return array(
            'current'   => $this->getOwner()->getGrowValue() ,
            'max'       => $this->getOwner()->getMaxGrowValue() ,
            'speed'     => $this->getOwner()->getGrowSpeed() ,
            'growPeriod'=> $this->getOwner()->growPeriod ,
            'price'     => $this->getOwner()->getPrice(),
            'maxPrice'  => $this->getOwner()->getMaxPrice(),
            'parts'     => $growPeriod<$this->getOwner()->growPeriod?$this->getDisplayData(false):false ,
        );
    }
}
