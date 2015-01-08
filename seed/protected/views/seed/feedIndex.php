<?php 
$isGrown = $seed->isGrown(); 
?>
    <div class="b_bg_05">
    	<a href="#" onclick="<?php echo !$isNative?'Common.goUrl(\''.$this->createUrl('garden/gardenList').'\');':'NativeApi.close();'; ?>" class="b_ico"></a>
		<!--返回按钮-->
        <div class="p5 ac"><span class="a_bar1"><?php echo Yii::t('Seed','feed title');?></span></div>
        <!--title-->
        <div class="b_con_05 clearfix">
        	<span id="SeedImageArea" style="float:left;margin-left:-30px; margin-top:-20px;"></span>
            <b style="width:100%;"><?php echo Yii::t('Seed','level name');?> <?php echo $seed->getMyLevel(); ?></b>
        </div>
        <!--宠物像-->
        <div class="b_con_04 clearfix" style="position: absolute;left: 280px;top: 80px;">
        	<div class="fl">
            	<span class="name"><?php echo Yii::t('Seed','seed name');?></span>
                <span class="tx01" id="SeedName"><?php echo $seed->getName();?></span>
                 <ul class="b_list_08 clearfix">
                    <li>
                        <img src="themes/images/imgb/ico_10.png" alt="">
                        <span style="color:#74532c;" id="SeedGrowLimitTime"></span>
                        <b><span class="black" id="SeedCurrentGrowValue"><?php echo $seed->getGrowValue();?></span>/<?php echo $seed->getMaxGrowValue();?></b>
                    </li>
                    <li>
                        <img src="themes/images/imgb/ico_7.png" alt="">
                        <b><span class="black" id="SeedCurrentPrice"><?php echo $seed->getPrice();?></span>/<?php echo $seed->getMaxPrice();?></b>
                    </li>
                </ul>
                <ul class="b_list_07 pt10">
                    <?php 
                    for( $i=4;$i>0;$i-- ){
                        if( $isGrown ) {
                            foreach( $seed->getAttributeLevels() as $k=>$v ) {
                                echo '<li id="compare_'.$i.'_'.$k.'" class="'.($v>=$i?'':'op04').'"><img src="themes/images/imgb/ico_'.$k.'.png"></li>'; 
                            }
                        }
                        else {
                            for( $j=1;$j<=6;$j++ ) {
                                echo $i==4?'<li class="empty"></li>':'<li class="op04"><img src="themes/images/imgb/ico_'.$j.'.png"></li>';
                            }
                        }
                    } 
                    ?>
                </ul>
            </div>
            <!--书左-->
            <div class="fr">
            	<h2><?php echo Yii::t('Seed','dress notice');?></h2>
                <p class="tx03" style="height:32px;"><?php echo Yii::t('Seed','dress notice description');?></p>
                <div class="tx02 clearfix">
                	<img src="themes/images/imgb/pic_23.png" alt="" id="equipImage" style="height:60px;">
                    <a href="#" <?php echo $isGrown?'class="a_btn03" onclick="showCatalogDialog()"':'class="a_btn03 op04"';?>><img src="themes/images/imgb/pic_27.png" alt=""></a>
                </div>
                <ul class="b_list_07 pt10">
                    <?php 
                    for( $i=4;$i>0;$i-- ){
                        if( $isGrown ) {
                            foreach( $seed->getAttributeLevels() as $k=>$v ) {
                                echo '<li id="equip_'.$i.'_'.$k.'" class="op04"><img src="themes/images/imgb/ico_'.$k.'.png"></li>'; 
                            }
                        }
                        else {
                            for( $j=1;$j<=6;$j++ ) {
                                echo $i==4?'<li class="empty"></li>':'<li class="op04"><img src="themes/images/imgb/ico_'.$j.'.png"></li>';
                            }
                        }
                    } 
                    ?>
                </ul>
            </div>
             <!--书右-->
        </div>
    	<!--书本内容-->
        <div class="b_con_06 clearfix" style="position: absolute;right:-40px;top:205px;">
                <div class="tx01" id="sureButtonsArea">
                <a href="#" class="a_btn03" onclick="feedReload()"><img src="themes/images/imgb/pic_28.png" alt=""/></a>
                <a href="#" class="a_btn03 ml10" onclick="feedSeedSure()"><img src="themes/images/imgb/pic_29.png" alt=""/></a>
            </div>

            <span id="selectedItem" style="position:relative;"></span>
            <b><span id="currentCount">0</span>/<span id="feedCount"><?php echo $seed->feedCount;?></span></b>
        </div>
    	<!--大碗-->
        
        <ul class="b_list_06" id="foodListArea">
            
                <?php 
                if( $isGrown ) { 
                ?>
                <li class="<?php echo $seed->feedCount==0?'op05':'';?>">
                    <a href="#" itemId="0" category="0" index="-1" class="addButton">
                        <img src="images/item/n_item_o025.png" width="140" alt="">
                    </a>
                    <div>
                        <b><?php echo Yii::t('Seed','feed free food name');?></b>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <p><?php echo Yii::t('Seed','feed free food desc');?></p>
                    </div>
                </li>
                    
                <?php
                }
                $index = 0 ;
                foreach ( $itemList as $arr ) {
                    if( empty($arr['pile']) ) $arr['pile']=0;
                    $itemCount = $arr['pile']*99+$arr['num'];
                    for($i=$arr['pile'];$i>=0;$i--) {
                        $index ++ ;
                        if( $itemCount>0&&$i==0&&$arr['num']==0 ) continue ;
            ?>
            <li class="<?php echo (!$isGrown&&$itemCount==0)||($isGrown&&$seed->feedCount==0)?'op05':'';?>">
             	<a href="#" itemId="<?php echo $arr['item']->id;?>" category="<?php echo $arr['item']->category;?>" index="<?php echo $index ;?>" class="addButton">
                	<img width="140" src="<?php echo $arr['itemMeta']->getImagePath();?>" alt="">
                    <em>X<span id="itemCount<?php echo $index;?>"><?php echo $i>0?99:$arr['num'] ;?></span></em>
                </a>
                <div>
                	<b><?php echo $arr['itemMeta']->getName(); ?></b>
                    <?php for($n=1;$n<=6;$n++) echo ' <i>'.($arr['item']->type==$n?'<img src="themes/images/imgb/ico_'.$n.'.png">':'').'</i> ';?> 
                    <p><?php echo $arr['item']->description;?></p>
                </div>
            </li>
            <?php  }
                }
            ?>
      
        </ul>
    <!--食物列表-->
    </div>

<script language="javascript">
var currentSeedId = <?php echo $seedId; ?>;
var selectedIds = [] ;
var feedCountMax = <?php echo $seed->feedCount;?>;
var feedCount = feedCountMax;
var isGrown = <?php echo $isGrown?1:0?>;
var growSpeed = <?php echo $seed->getGrowSpeed(); ?>; 
var selectedDressId = <?php echo $dressId; ?>;
var seedAttributes = <?php echo json_encode( $seed->getFeedAttributes() );?>;
var removeIndexs = [] ;

LoadAction.push(function(){
    var itemCount = $('ul.b_list_06>li').length;
    if( itemCount>3 ) {
        var width = 236*(itemCount+2) ;
        $('#foodListArea').width( width );
        Flipsnap('#foodListArea', {position: 'x',distance:236,maxPoint:Math.max(1,itemCount-2)});
    }
    <?php if( !empty($_REQUEST['animation']) ) { ?>
    SeedAnimation('SeedImageArea',<?php echo $seed->getAnimationData(true);?>,1);
    <?php } ?>
});

var showCatalogDialog = function( page ){
    if( !page ) page = 1 ;
    ajaxLoader.get('<?php echo $this->createUrl('catalog/feedDialog',array('seedId'=>$seedId));?>&page='+page,function(data){ CommonDialog.create('feedCatalogDialog',data); });
}

$(document).ready(function(){
    if( !isGrown && <?php echo $supplyPower; ?>>0 ){
        SeedTimer.start('SeedGrowLimitTime',<?php echo $seed->getGrowLimitSecond();?>,Common.refreshCurrentPage);
        //$('#growLimitTime').html();
        if( window.growingSeed ) {
            window.growingSeed.clear();
        }
        window.growingSeed = new SeedGrowing(<?php echo json_encode(array_merge(array('url'=>$this->createUrl('seed/growData',array('seedId'=>$seedId,'growPeriod'=>$seed->growPeriod))),$seed->getGrowValueData()));?>,function(){},1);
    }
    <?php if( !empty($dressId)&&!empty($dressData) ) {
        echo 'setDressData('.json_encode($dressData->getAttributeSize()).');';
        echo '$("#equipImage").attr("src","'.$dressData->getImage().'");';
    } 
    ?>
    $('#sureButtonsArea').hide();
    <?php if( !empty($seed) ) echo 'SeedUnit("SeedImageArea",'.$seed->getDisplayData().',1);'; ?> 
    var checkCount = isGrown ;

    $('.addButton').click(function() {
        if( $(this).parent().attr('class')=='op05' ) {
            CommonDialog.confirm('<?php echo Yii::t('Seed','no item to shop');?>',function(){
                Common.goUrl('<?php echo $this->createUrl('shop/index',array('category'=>1));?>');
            });
            return ;
        }
        var itemId = $(this).attr('itemId') ;
        var index = $(this).attr('index') ;
        var itemCount = parseInt($('#itemCount'+index).html()) ;
        if( itemCount<1 ) {
            return ;
        }
        else {
            if( isGrown ) {
                if( checkCount&&feedCount<=0 ) {
                    return ;
                }
                if( checkCount ) feedCount -- ;
                if( feedCount==0 ) {
                    $('.addButton').parent().addClass('op04');
                }
                if( selectedIds[itemId] ) {
                    selectedIds[itemId] ++ ;
                }
                else {
                    selectedIds[itemId] = 1 ;
                }
                var sum = 0 ;
                for( i in selectedIds ){ sum+=selectedIds[i]; }
                $('#currentCount').html( sum );
                var newCount = itemCount-1 ;
                if( newCount <= 0 ) {
                    $(this).parent().addClass('op04');
                    removeIndexs[removeIndexs.length] = $(this);
                }
                $('#itemCount'+index).html( newCount )
                if( sum>0 ){
                    $('#sureButtonsArea').show();
                }
                var positions = {
                    1 : {
                        position:'absolute' ,
                        width:100 ,
                        height:100 ,
                        left: 60 ,
                        top: -10 
                    } ,
                    2 : {
                        position:'absolute' ,
                        width:90 ,
                        height:90 ,
                        left: 0 ,
                        top: -15 
                    } ,
                    3 : {
                        position:'absolute' ,
                        width:90 ,
                        height:90 ,
                        left: 120 ,
                        top: -15 
                    } ,
                    4 : {
                        position:'absolute' ,
                        width:100 ,
                        height:100 ,
                        left: 35 ,
                        top: -3 
                    } ,
                    5 : {
                        position:'absolute' ,
                        width:100 ,
                        height:100 ,
                        left: 85 ,
                        top: -3 
                    } ,
                };
                var img = $(this).children('img').clone();
                img.css( positions[sum] );
                $('#selectedItem').append( img );
                compareGrow();
            }
            else {
                selectedIds[itemId] = 1 ;
                feedSeedSure();
            }
        }
    });

});

var isFeeding = false ;
var feedSeedSure = function(){
    if( isFeeding ) {
        return ;
    }
    isFeeding = true ;
    setTimeout(function(){isFeeding=false;},2000);
    if( selectedIds.length<1 ) {
        return ;
    }
    else {
        var url = '<?php echo $this->createUrl('/seed/feedApply',array('seedId'=>$seedId));?>';
        for( id in selectedIds ) {
            url += '&items['+id+']='+selectedIds[id] ;
        }
        if( isGrown ) {
            ajaxLoader.get(url,function(data){
                if( data.feedCount<1&&!data.isEquiped ) {
                    CommonDialog.alert('<?php echo Yii::t('Seed','seed not equiped');?>');
                }
                else {
                    SeedAnimation('SeedImageArea',data.animationData,1);
                }
                feedCountMax = data.feedCount;
                seedAttributes = data.attributes;
                for( i in removeIndexs ){
                    removeIndexs[i].remove();
                }
                selectedIds = [] ;
                feedReload();
            });
        }
        else {
            CommonDialog.confirm( '<?php echo Yii::t('Seed','feed the item?');?>',function(){
                ajaxLoader.get(url,function(data){
                    isFeeding=false;
                    if( checkGuideStatus() ) {
                        return ;
                    }
                    if( data.growData['current']>=data.growData['max'] ) {
                        var url = '<?php echo $this->createUrl('seed/feedIndex',array('seedId'=>$seedId,'native'=>$isNative?1:0,'animation'=>1));?>' ;
                        if(!<?php echo $isCurrentGuide&&!$isGrown?1:0;?>) Common.goUrl( url );
                        if( window.growingSeed ) {
                            window.growingSeed.clear();
                        }
                    }
                    else {
                        if( window.growingSeed ) {
                            window.growingSeed.restart(data.growData);
                        }
                        else {
                            $('#SeedCurrentGrowValue').html(data.growData['current']);
                            $('#SeedCurrentPrice').html(data.growData['price']);
                        }
                        for( id in selectedIds ) {
                            var countId = '#itemCount'+$('.addButton[itemid='+id+']').attr('index');
                            var newCount = parseInt($(countId).html())-1 ;
                            $(countId).html( newCount );
                            if( newCount<=0 ) {
                                $('.addButton[itemid='+id+']').first().parent().addClass('op05');
                            }
                        }
                        selectedIds = [] ;
                        SeedAnimation('SeedImageArea',data.animationData,1);
                        SeedTimer.clear2('SeedGrowLimitTime');
                        SeedTimer.start('SeedGrowLimitTime',data.growSeconds,Common.refreshCurrentPage);
                        $('#SeedName').html(data.seedName);

                    }
                });
            });
        }
    }
}
var feedReload = function(){
    /*if( selectedIds.length<1 ) {
        return ;
    }*/

    for( i in selectedIds ){
        var selectedCount = selectedIds[i];
        $('a[itemId='+i+']').each(function(){
            if( selectedCount<=0 ) return ;
            var index = parseInt($(this).attr('index'));
            var itemCount = parseInt($('#itemCount'+index).html()) ;
            itemCount += selectedCount;
            selectedCount = itemCount -  Math.min(itemCount,99) ;
            if( selectedCount>0 ) {
                itemCount = 99 ;
            }
            $('#itemCount'+index).html(itemCount);
        });
    }
    selectedIds = [] ;
    $('#sureButtonsArea').hide();
    $('#currentCount').html( '0' );
    feedCount = feedCountMax;
    $('#selectedItem').empty();
    compareGrow();
    $('#feedCount').html(feedCountMax);
    if( feedCount>0 ){
        $('.addButton').parent().removeClass('op04');
    }
}

<?php
    if( !$isGrown ) {
        echo "var itemEffect = []";
    }
    else {
        $effectArray = array();
        foreach( $itemList as $arr ) {
            $effectArray[$arr['item']->id] = $arr['item']->effect['attributes'] ;
        }
        echo "var itemEffect = ".json_encode($effectArray).";\n";
        //echo "var seedAttributes = ".json_encode( $seed->getFeedAttributes() ).";\n";
        echo "var maxAttributeValue = ".$seed->getMaxAttributeValue().";\n";
        echo "var maxAttributeSize = ".SeedDressData::getMaxValue().";\n";
    }
?>

var compareGrow = function(){
    var attributes = [] ;
    for( k in seedAttributes ) {
        attributes[k] = seedAttributes[k];
    }
    for( id in selectedIds ) {
        var effect = itemEffect[id];
        for( k in effect ) {
            attributes[k] += effect[k]*selectedIds[id] ;
        }
    } 
    
    var result = [] ;
    for( n in attributes ) {
        result[n] = Math.floor(attributes[n]/maxAttributeValue*maxAttributeSize);
    }

    for( i=1;i<=4;i++ ){
        for( j=1;j<=6;j++ ) {
            if( result[j]>=i ) {
                $('#compare_'+i+'_'+j).removeClass('op04');
            }
            else {
                $('#compare_'+i+'_'+j).addClass('op04');
            }
        }
    }
}

var setDressData = function(data) {
    for( i=1;i<=4;i++ ) {
        for(j=1;j<=6;j++) {
            var elemId = '#equip_'+i+'_'+j;
            if( data[j]>=i ) {
                $(elemId).removeClass('op04');
            }
            else {
                $(elemId).addClass('op04');
            }
        }
    }
}


function checkGuideStatus(){
    if( <?php echo $isCurrentGuide&&!$isGrown?1:0;?> ){
        feedGuide.over();
        return true ;
    }
    else {
        return false ;
    }
}

</script>



<?php 
/** 新手引导部分 **/ 
if( $isCurrentGuide&&!$isGrown ) {
?>
<div style="width:421px; height:337px; overflow:hidden; position:absolute;bottom:0;right:0;z-index:202;">
    <div class="b_text06 b_text06_1" style="float:left" id="dialogContent">
        <i class="text06pic"></i>
        <div class="long">
            <span id="feedGuideMessage">
            </span>
            <em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
        </div>
    </div>
</div>
<script language="javascript">
$(document).ready(function(){
    var element = document.createElement('div');
    $(element).attr('id','feedGuideMessage')
    $('#page').append(element);
    //.css({'position':'absolute','left':150,'top':50,'z-index':feedGuide.zIndex++});
    feedGuide.nextStep();
});
LoadAction.push(function(){
    $('div.guide_main_cover').show().css('z-index',200);
});

var feedGuide = {
    step:0,
    zIndex:201,
    nextStep:function(){
        var self = this ;
        self.step ++ ;
        switch (self.step)
        {
            case 1:
                self.showFeedMessage();
                break;
            case 2:
                break;
            default:
                //CommonDialog.alert('Step error!');
        } 
    },
    showFeedMessage:function(){
        var self = this ;
        var dataString = '<?php echo Yii::t('GuideMessage','message_41');?>';
        var tempArray = dataString.split('|');
        var messageCount = 0; 
        var nextFeedMessage = function(){
            $('#feedGuideMessage').html( tempArray[messageCount] ).parent().unbind('click').click(function(){
                nextFeedMessage();
                $(this).unbind('click');
            });
            messageCount ++ ;

            if( messageCount==tempArray.length ){
                $('#feedGuideMessage').css({'left':150,'top':50}).unbind('click');
                var elem = document.createElement('div');
                $(elem).addClass('b_text07 left').attr('style','display:inline;position:absolute;left:170px;top:420px;z-index:'+(self.zIndex++)+';').append('<em class="new_hand"><span class="new_hand1"></span><span class="new_hand2"></span></em> <em class="star"></em> <em class="fangxiang new_left"></em></div>').click(function(){
                    var itemId = $('ul.b_list_06>li').first().children('a').attr('itemId');
                    selectedIds[itemId] = 1 ;
                    feedSeedSure();
                    //$(elem).remove();
                });
                $('#page').append(elem);
                $('.b_text05goon').remove();
                return ;
            }
        }
        nextFeedMessage();
    },
    over:function(){
        $('#feedGuideMessage').parent().parent().parent().remove();
        $('#page').empty();
        setTimeout(function(){
            NativeApi.close();
        },50);
        //NativeApi.delay(true);
        //ajaxLoader.get('<?php echo $this->createUrl('guide/saveStatus&accessLevel=49');?>',function(){
            //$('#page').empty();
            //NativeApi.close().doRequest();
        //})
    },
    appendHand : function(){
        $('#feedGuideMessage').append('<div style="width:100%;clear:both;"><em class="new_hand"><span class="new_hand1"></span><span class="new_hand2"></span></em></div>');
    }
}
</script>
<?php } ?>
