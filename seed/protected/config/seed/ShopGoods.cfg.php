<?php
$goodsArray = array() ;
$ResItemArray = include(dirname(__FILE__)."/ResItem.cfg.php");
foreach( $ResItemArray as $id=>$item ) {
    if( $item['canBuy']!=1 ) continue ;
    $goodsArray[$id+10000] = array(
        'goodsType' => 'item' ,
        'internalId'=> $id ,
        'moneyType' => $item['moneyType'] , 
        'category'  => '2' ,
        'data'      => $item ,
    );
}

$UseItemArray = include(dirname(__FILE__)."/UseItem.cfg.php");
foreach( $UseItemArray as $id=>$item ) {
    if( $item['canBuy']!=1 ) continue ;
    if( $item['category']<0 ) continue ;
    $category = $item['category']==6?6:1 ;
    $goodsArray[$id+20000] = array(
        'goodsType' => 'item' ,
        'internalId'=> $id ,
        'moneyType' => $item['moneyType'] , 
        'category'  => $category ,
        'data'      => $item ,
    );
}

$DecoItemArray = include(dirname(__FILE__)."/DecoItem.cfg.php");
foreach( $DecoItemArray as $id=>$item ) {
    if( $item['canBuy']!=1 ) continue ;
    $goodsArray[$id] = array(
        'goodsType' => 'item' ,
        'internalId'=> $id ,
        'moneyType' => $item['moneyType'] , 
        'category'  => '3' ,
        'data'      => $item ,
    );
}


    
    /** 购买金种子 **/
    $purchaseArray = include(dirname(__FILE__)."/ItunesPurchase.cfg.php");
    $purchaseCount = 40001;
    foreach( $purchaseArray['products'] as $key=>$arr ) {
        $temp = explode('.',$key);
        $internalId = end($temp);
        $goodsArray[$purchaseCount]  = array(
                'goodsType' => 'gold' ,
                'moneyType' => 0 , 
                'image'     => 'images/goldIcon/'.$purchaseCount.'.png',
                'internalId'=> $internalId ,
                'effect'    => 1 ,
                'category'  => '4' ,
                'price'     => $arr['price'] ,
                'data'      => $arr ,
        );
        $purchaseCount++ ;
    }
    
    /** 清除繁殖CD时间 **/
    $goodsArray[80001]  = array(
            'goodsType' => 'breedCDTime' ,
            'moneyType' => 1 , 
            'effect'    => 1 ,
            'category'  => '89' ,
            'price'     => 300 ,
    );

    /** 直接购买邮票 **/
    $goodsArray[80034]  = $goodsArray[20034];
    $goodsArray[80035]  = $goodsArray[20035];
    $goodsArray[80034]['category']  = '90';
    $goodsArray[80035]['category']  = '90';
    


    /** 回复全部行动力 **/
    $goodsArray[90001]  = array(
            'goodsType' => 'actionPoint' ,
            'moneyType' => 1 , 
            'effect'    => 1 ,
            'category'  => '99' ,
            'price'     => $UseItemArray[38]['price'] ,
    );

    /** 回复一半行动力 **/
    $goodsArray[90002]  = array(
            'goodsType' => 'actionPoint' ,
            'moneyType' => 1 , 
            'effect'    => 0.5 ,
            'category'  => '99' ,
            'price'     => $UseItemArray[37]['price'] ,
    );

return $goodsArray ;
