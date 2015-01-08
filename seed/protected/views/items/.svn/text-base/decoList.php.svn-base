<script language="javascript"> 
    <?php if ($this->actionType == REQUEST_TYPE_AJAX) { ?>
            shopScroll();
    <?php } else { ?>
        LoadAction.push( function(){
            shopScroll();
        });
    <?php } ?>
                            
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
                            
    var myScroll;
    var shopScroll = function(){
        var countSum = 0 ;
        var clickedGoods ;
        $('#a_list>li').each(function(){
            var self = this ;
            countSum ++ ;
            $(self).children('.clickArea').click(function(){
                var clickId = $(self).attr('id');
                if( clickId == clickedGoods ) {
                    $(self).removeClass('hover01').children('.dataArea').hide();
                    clickedGoods = null ;
                }
                else {
                    $(self).addClass('hover01').children('.dataArea').show();
                    $('#'+clickedGoods).removeClass('hover01').children('.dataArea').hide();
                    clickedGoods = clickId ;
                }
            });
        });
        if( countSum<=4 ) return ;
        var wrapWidth = countSum*220 ;
        $('#a_list').width(wrapWidth);

        myScroll = new iScroll("wrapper",{hScrollbar:false, vScrollbar:false});

    }
</script>
<style type="text/css">
    .common_diaglog .frame_confirm ,.common_diaglog .frame_alert {
        margin-top:0px;
    }
</style>
<?php
if (isset($decoList)) {
    if ($category == 5) {
        uasort($decoList, "cup_sort");
    } else {
        uasort($decoList, "my_sort");
    }
}

function my_sort($a, $b) {
    if ($a['item']->useLevel == $b['item']->useLevel)
        return 0;
    return ($a['item']->useLevel < $b['item']->useLevel) ? -1 : 1;
}

function cup_sort($a, $b) {
    if ($a['item']->id == $b['item']->id)
        return 0;
    return ($a['item']->id < $b['item']->id) ? -1 : 1;
}
?>
<?php if ($category == 7 || $category == 8 || $category == 9) { ?>
    <?php $player = Yii::app()->objectLoader->load('Player', $this->playerId); ?>
    <div class="b_bg_04">
        <div class="b_con_12 clearfix">
            <?php if ($category == 7) { ?>
                <span style="left:20px;" class="a_bar1"><?php echo Yii::t('Item', 'decoration1'); ?></span>
            <?php } else if ($category == 8) { ?>
                <span style="left:130px;" class="a_bar1"><?php echo Yii::t('Item', 'decoration2'); ?></span>
            <?php } else if ($category == 9) { ?>
                <span style="left:270px;" class="a_bar1"><?php echo Yii::t('Item', 'decoration3'); ?></span>
            <?php } ?>
            <div id="wrapper" style="width:1000px; height: 310px; overflow:hidden;">
                <ul class="b_list_01" id="a_list">
                    <?php if (isset($decoList)) { ?>
                        <?php foreach ($decoList as $decoId => $deco) { ?>
                            <?php if ($deco['item']->canBuy == 0 && $deco['num'] == 0) continue; ?>
                            <?php if ($deco['item']->special == 0) { ?>
                                <?php if ($deco['item']->useLevel <= $player->level) { ?>
                                    <li id="goodsData<?php echo $decoId; ?>">
                                        <div class="clickArea">
                                            <?php if ($deco['num'] != 0) { ?>
                                                <em id ="em<?php echo $decoId; ?>"><?php echo $deco['num'] > 99 ? '99+' : $deco['num']; ?></em>
                                            <?php } ?>
                                            <?php if (($addValidate['littleCount'] >= MAXLITTLEDECONUM && $deco['item']->sizeType == 1) || ($addValidate['middelCount'] >= MAXMIDDELDECONUM && $deco['item']->sizeType == 2) || ($addValidate['bigCount'] >= MAXBIGDECONUM && $deco['item']->sizeType == 3) || ($deco['num'] == 0 && $deco['canBuy'] == 0)) { ?>
                                                <a href="#" onclick="showIsFullTip('<?php echo Yii::t('Item', 'decoration' . $deco['item']->sizeType); ?>','<?php echo $deco['canBuy']; ?>','<?php echo str_replace(substr($deco['item']->image, -4), '', $deco['item']->image); ?>','<?php echo $deco['num']; ?>','<?php echo $deco['item']->sellPrice; ?>','<?php echo $deco['item']->moneyType; ?>');">
                                                <?php } else { ?>
                                                    <a href="native://name=<?php echo str_replace(substr($deco['item']->image, -4), '', $deco['item']->image); ?>&count=<?php echo $deco['num']; ?>&price=<?php echo $deco['item']->sellPrice; ?>&action=newdeco&isFull=false">
                                                    <?php } ?>
                                                    <img src="<?php echo $deco['itemMeta']->getMidImagePath(); ?>" alt="">
                                                </a>
                                                <?php if ($deco['num'] > 0 && $deco['item']->canSell == 1) { ?>
                                                    <span class="b_sale">
                                                        <img src="themes/images/imga/ico3.png">
                                                        <img src="themes/images/imgb/ico_7.png" alt=""><?php echo $deco['item']->sellPrice; ?>
                                                    </span>
                                                <?php } ?>
                                                <span>
                                                    <?php if ($deco['item']->moneyType == 0) { ?>
                                                        <img src="themes/images/imgb/ico_7.png" alt="">
                                                    <?php } else { ?>
                                                        <img src="images/money.png" alt="">
                                                    <?php } ?>
                                                    <?php echo $deco['item']->price; ?>
                                                </span>
                                                <b><?php echo $deco['item']->name; ?></b>
                                        </div>
                                        <span class="sale clearfix">
                                            <span class="<?php echo ($deco['num'] > 0) ? 'tx01' : 'tx02 mt20'; ?>">
                                                <img src="themes/images/imgb/ico_10.png" alt=""><?php echo Yii::t('Item', 'growth'); ?><?php if ($deco['num'] > 0) { ?><br><?php } ?>
                                                <b class="pl10"><?php echo $deco['item']->grow; ?></b>
                                            </span>
                                            <?php if ($deco['num'] > 0 && $deco['item']->canSell == 1) { ?>
                                                <a id ="href<?php echo $decoId; ?>" onclick="sell(<?php echo $deco['item']->moneyType; ?>,<?php echo $decoId; ?>,<?php echo $deco['item']->sellPrice; ?>,<?php echo $deco['item']->canBuy; ?>);return false" href="#" class="a_btn03 fr"><img src="themes/images/imga/ico3.png"></a>
                                            <?php } ?>
                                        </span>
                                    </li>
                                <?php } else { ?>
                                    <li class="lock" id="goodsData<?php echo $decoId; ?>">
                                        <div>
                                            <?php if ($deco['num'] != 0) { ?>
                                                <em id ="em<?php echo $decoId; ?>"><?php echo $deco['num'] > 99 ? '99+' : $deco['num']; ?></em>
                                            <?php } ?>
                                            <img class="op05" src="<?php echo $deco['itemMeta']->getMidImagePath(); ?>" alt="">
                                            <span style="background:none">
                                            </span>
                                            <b><?php echo $deco['item']->name; ?></b>
                                        </div>
                                        <span class="sale clearfix">
                                            <span class="<?php echo ($deco['num'] > 0) ? 'tx01' : 'tx02 mt20'; ?>">
                                                <img class="op05" src="themes/images/imgb/ico_10.png" alt=""><?php echo Yii::t('Item', 'growth'); ?><?php if ($deco['num'] > 0) { ?><br><?php } ?>
                                                <b class="pl10"><?php echo $deco['item']->grow; ?></b>
                                            </span>
                                            <?php if ($deco['num'] > 0) { ?>
                                                <a id ="href<?php echo $decoId; ?>" onclick="sell(<?php echo $deco['item']->moneyType; ?>,<?php echo $decoId; ?>,<?php echo $deco['item']->sellPrice; ?>,<?php echo $deco['item']->canBuy; ?>);return false" href="#" class="a_btn03 fr"><img src="themes/images/imga/ico3.png"></a>
                                            <?php } ?>
                                        </span>
                                        <em class="level">LEVEL <?php echo $deco['item']->useLevel; ?></em>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <?php if ($category == 4) { ?>
            <?php $player = Yii::app()->objectLoader->load('Player', $this->playerId); ?>
            <div class="b_bg_04">
                <div class="b_con_12 clearfix">
                    <span style="left:390px;" class="a_bar1"><?php echo Yii::t('Item', 'background storage'); ?></span>
                    <div>
                        <div id="wrapper" style="width:1000px; height: 310px; overflow:hidden;">
                            <ul class="b_list_01" id="a_list">
                                <?php if (isset($decoList)) { ?>
                                    <?php foreach ($decoList as $decoId => $deco) { ?>
                                        <?php if ($deco['item']->useLevel <= $player->level) { ?>
                                            <li id="goodsData<?php echo $decoId; ?>">
                                                <div class="clickArea">
                                                    <?php if ($deco['num'] != 0) { ?>
                                                        <em id ="em<?php echo $decoId; ?>"><?php echo $deco['num'] > 99 ? '99+' : $deco['num']; ?></em>
                                                    <?php } ?>
                                                    <?php $garden = Yii::app()->objectLoader->load('Garden', $gardenId); ?>
                                                    <?php if ($garden->backGround == $decoId) { ?><span class="yes"></span><?php } ?>
                                                    <?php if (($deco['num'] == 0 && $deco['canBuy'] == 0)) { ?>
                                                        <a href="#" onclick="showIsFullTip2('<?php echo $deco['canBuy']; ?>','<?php echo str_replace(substr($deco['item']->image, -4), '', $deco['item']->image); ?>', '<?php echo $deco['num'] ?>','<?php echo $deco['mail']; ?>','<?php echo $deco['shop']; ?>','<?php echo $deco['isSame']; ?>','<?php echo $deco['item']->moneyType; ?>')">
                                                        <?php } else { ?>
                                                            <a href="#" onclick="buyBackground('<?php echo str_replace(substr($deco['item']->image, -4), '', $deco['item']->image); ?>', '<?php echo $deco['num'] ?>','<?php echo $deco['mail']; ?>','<?php echo $deco['shop']; ?>','<?php echo $deco['isSame']; ?>')">
                                                            <?php } ?>
                                                            <img src="<?php echo $deco['itemMeta']->getMidImagePath(); ?>" alt="">
                                                        </a>
                                                        <?php if ($deco['num'] > 0 && $deco['item']->canSell == 1) { ?>
                                                            <span class="b_sale">
                                                                <img src="themes/images/imga/ico3.png">
                                                                <img src="themes/images/imgb/ico_7.png" alt=""><?php echo $deco['item']->sellPrice; ?>
                                                            </span>
                                                        <?php } ?>
                                                        <span>
                                                            <?php if ($deco['item']->moneyType == 0) { ?>
                                                                <img src="themes/images/imgb/ico_7.png" alt="">
                                                            <?php } else { ?>
                                                                <img src="images/money.png" alt="">
                                                            <?php } ?>
                                                            <?php echo $deco['item']->price; ?>
                                                        </span>
                                                        <b><?php echo $deco['item']->name; ?></b>
                                                </div>
                                                <span class="sale clearfix">
                                                    <?php if ($deco['num'] > 0 && $deco['item']->canSell == 1) { ?>
                                                        <a id ="href<?php echo $decoId; ?>" onclick="sell(<?php echo $deco['item']->moneyType; ?>,<?php echo $decoId; ?>,<?php echo $deco['item']->sellPrice; ?>,<?php echo $deco['item']->canBuy; ?>);return false" href="#" class="a_btn03 fr"><img src="themes/images/imga/ico3.png"></a>
                                                    <?php } ?>
                                                </span>
                                            </li>
                                        <?php } else { ?>
                                            <li class="lock" id="goodsData<?php echo $decoId; ?>">
                                                <div>
                                                    <?php if ($deco['num'] != 0) { ?>
                                                        <em id ="em<?php echo $decoId; ?>"><?php echo $deco['num'] > 99 ? '99+' : $deco['num']; ?></em>
                                                    <?php } ?>
                                                    <img class="op05" src="<?php echo $deco['itemMeta']->getMidImagePath(); ?>" alt="">
                                                    <span style="background:none">
                                                    </span>
                                                    <b><?php echo $deco['item']->name; ?></b>
                                                </div>
                                                <span class="sale clearfix">
                                                    <?php if ($deco['num'] > 0) { ?>
                                                        <a id ="href<?php echo $decoId; ?>" onclick="sell(<?php echo $deco['item']->moneyType; ?>,<?php echo $decoId; ?>,<?php echo $deco['item']->sellPrice; ?>,<?php echo $deco['item']->canBuy; ?>);return false" href="#" class="a_btn03 fr"><img src="themes/images/imga/ico3.png"></a>
                                                    <?php } ?>
                                                </span>
                                                <em class="level">LEVEL <?php echo $deco['item']->useLevel; ?></em>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($category == 5) { ?>
                    <div class="b_bg_04">
                        <div class="b_con_12 clearfix">
                            <span style="left:590px;" class="a_bar1"><?php echo Yii::t('Item', 'cup storage'); ?></span>
                            <div id="wrapper" style="width:1000px; height: 310px; overflow:hidden;">
                                <ul class="b_list_01" id="a_list">
                                    <?php if (isset($decoList)) { ?>
                                        <?php $showCount = count($decoList); ?>
                                        <?php foreach ($decoList as $decoId => $deco) { ?>
                                            <?php if ($deco['item']->special == 0) { ?>
                                                <li id="goodsData<?php echo $decoId; ?>" <?php if ($deco['num'] == 0 && (!isset($cupState[$decoId]) || $cupState[$decoId] == 3)) echo 'class="lock"' ?>>
                                                    <div class="clickArea">
                                                        <?php if (isset($cupState[$decoId]) && $cupState[$decoId] == 0) echo '<span class="yes"></span>'; ?>
                                                        <?php if (isset($cupState[$decoId]) && $cupState[$decoId] == 1) echo '<samp class="mod_icon icon_new"></samp>'; ?>
                                                        <?php if (($addValidate['littleCount'] >= MAXLITTLEDECONUM && $deco['item']->sizeType == 1) || ($addValidate['middelCount'] >= MAXMIDDELDECONUM && $deco['item']->sizeType == 2) || ($addValidate['bigCount'] >= MAXBIGDECONUM && $deco['item']->sizeType == 3)) { ?>
                                                            <a href="#" onclick="showIsFullTip3('<?php echo Yii::t('Item', 'decoration' . $deco['item']->sizeType); ?>','<?php echo str_replace(substr($deco['item']->image, -4), '', $deco['item']->image); ?>','<?php echo $deco['num']; ?>','<?php echo $deco['item']->price; ?>');">
                                                                <img <?php if ($deco['num'] == 0 && (!isset($cupState[$decoId]) || $cupState[$decoId] == 3)) echo 'class="op05"' ?> src="<?php echo $deco['itemMeta']->getMidImagePath(); ?>" alt="">
                                                            </a>
                                                        <?php } else { ?>
                                                            <?php if (isset($cupState[$decoId]) && $cupState[$decoId] != 0) { ?>
                                                                <a href="native://name=<?php echo str_replace(substr($deco['item']->image, -4), '', $deco['item']->image); ?>&count=<?php echo $deco['num']; ?>&price=<?php echo $deco['item']->price; ?>&action=newdeco&isFull=false">
                                                                <?php } ?>
                                                                <img <?php if ($deco['num'] == 0 && (!isset($cupState[$decoId]) || $cupState[$decoId] == 3)) echo 'class="op05"' ?> src="<?php echo $deco['itemMeta']->getMidImagePath(); ?>" alt="">
                                                                <?php if (isset($cupState[$decoId]) && $cupState[$decoId] != 0) { ?>
                                                                </a>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <b><?php echo $deco['item']->name; ?></b>
                                                    </div>
                                                    <span class="sale clearfix">
                                                        <span class="tx01">
                                                            <img src="themes/images/imgb/ico_10.png" alt=""><?php echo Yii::t('Item', 'growth'); ?><?php if ($deco['num'] > 0) { ?><br><?php } ?>
                                                            <b class="pl10"><?php echo $deco['item']->grow; ?></b>
                                                        </span>
                                                        <span <?php echo ($deco['num'] == 0 && (!isset($cupState[$decoId]) || $cupState[$decoId] == 3)) ? 'class="mod_icon icon_02"' : 'class="mod_icon icon_01"' ?>></span>
                                                    </span>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                        <script>
                            var canBuyFlag;
                            function give(itemId)
                            {
                                selectFriend.url = '<?php echo $this->createUrl('/friend/friendSelect'); ?>';
                                selectFriend.show( function(){
                                    CommonDialog.confirm('<?php echo Yii::t('Item', 'Are you sure give it？'); ?>',function(){
                                        ajaxLoader.get('<?php echo $this->createUrl('Items/Give'); ?>&friendId='+selectFriend.selectId+'&itemId='+itemId,refreshNum);
                                    } );
                                });
                            }
                            function sell(type,itemId,price,canBuy)
                            {
                                if(type == 1 || canBuy==0)
                                {
                                    CommonDialog.confirm('<?php echo Yii::t('Item', 'will you want sell the item?the price is:'); ?><span class="yezi">'+price+'</span>',function(){
                                        ajaxLoader.get('<?php echo $this->createUrl('Items/Sell'); ?>&itemId='+itemId,refreshSellNum);
                                    });
                                }else
                                {
                                    ajaxLoader.get('<?php echo $this->createUrl('Items/Sell'); ?>&itemId='+itemId,refreshSellNum);
                                }
                            }
                            function showIsFullTip(sizeType,canBuy,name,count,price,moneyType)
                            {                   
//                                getCanBuy(name);
                                                                
                                if(canBuy == 0&&count == 0)
                                {
                                    var htmlstr = '<span class="yezi"><?php echo Yii::t('Item', 'the gold shortage unable to purchase'); ?></span>';
                                    if(moneyType == 1)
                                    {
                                        htmlstr = '<span class="jinzz"><?php echo Yii::t('Item', 'the gold shortage unable to purchase'); ?></span>';
                                    }
                                    
                                    CommonDialog.alert(htmlstr);
                                    return;
                                }else
                                {
                                    var decofull = '<?php echo Yii::t('Item', 'decoration use upper limit is full already, you can only view, can not put'); ?>';
                                                                
                                    decofull = decofull.replace('[x]',sizeType); 

                                    CommonDialog.alert(decofull,function(){                                                                        
                                        var params = {
                                            "name":name ,
                                            "count":count ,
                                            "price":price,
                                            "action":"newdeco",
                                            "isFull":"true"
                                        }
                                        NativeApi.callback(params);
                                    });  
                                }
                            }
                            function showIsFullTip2(canBuy,name,count,mail,shop,isSame,moneyType)
                            {           
//                                getCanBuy(name);
                                
                                if(canBuy == 0&&count == 0)
                                {
                                    var htmlstr = '<span class="yezi"><?php echo Yii::t('Item', 'the gold shortage unable to purchase'); ?></span>';
                                    if(moneyType == 1)
                                    {
                                        htmlstr = '<span class="jinzz"><?php echo Yii::t('Item', 'the gold shortage unable to purchase'); ?></span>';
                                    }
                                    
                                    CommonDialog.alert(htmlstr);
                                    return;
                                }
                                var params = {
                                    "background":name ,
                                    "count":count ,
                                    "mail":mail ,
                                    "shop":shop,
                                    "action":"changebackground",
                                    "isFull":"true"
                                }
                                NativeApi.callback(params);
                            }
                            function buyBackground(name,count,mail,shop,isSame)
                            {
                                if(isSame == '1')
                                {
                                    CommonDialog.confirm('<?php echo Yii::t('Item', 'Are you sure buy the same background？'); ?>',function(){
                                        var params = {
                                            "background":name ,
                                            "count":count ,
                                            "mail":mail ,
                                            "shop":shop,
                                            "action":"changebackground",
                                            "isFull":"false"
                                        }
                                        NativeApi.callback(params);
                                    });
                                }else
                                {
                                    var params = {
                                        "background":name ,
                                        "count":count ,
                                        "mail":mail ,
                                        "shop":shop,
                                        "action":"changebackground",
                                        "isFull":"false"
                                    }
                                    NativeApi.callback(params);
                                }
                            }
                            function showIsFullTip3(sizeType,name,count,price)
                            {
                                var decofull = '<?php echo Yii::t('Item', 'decoration use upper limit is full already, you can only view, can not put'); ?>';
                                                                
                                decofull = decofull.replace('[x]',sizeType); 
                                                                
                                CommonDialog.alert(decofull,function(){
                                    var params = {
                                        "name":name ,
                                        "count":count ,
                                        "price":price,
                                        "action":"newdeco",
                                        "isFull":"true"
                                    }
                                    NativeApi.callback(params);
                                });
                            }
                            function refreshSellNum(data)
                            {
                                CommonDialog.alert('<?php echo Yii::t('Item', 'deco sell suceessful'); ?><span class="yezi">'+data.sellPrice+'</span>',function(){
//                                    var value = $("#em"+data.itemId).text();
//                                    var numValue = parseInt(value.replace('X',''));
//                                    var numValue = numValue - 1;
//                                    var newString = numValue;
//                                    $("#em"+data.itemId).text(newString);
//                                    if(numValue == 0)
//                                    {
                                        ajaxShow();
//                                    }
                                });
                            }
                            function refreshNum(data)
                            {
//                                var value = $("#em"+data.itemId).text();
//                                var numValue = parseInt(value.replace('X',''));
//                                var numValue = numValue - 1;
//                                var newString = numValue;
//                                $("#em"+data.itemId).text(newString);
//                                if(numValue == 0)
//                                {
                                    ajaxShow();
//                                }
                            }
                            function getCanBuy(name)
                            {
                                ajaxLoader.get('<?php echo $this->createUrl('Items/getCanBuy'); ?>&name='+name,canBuyCallback);
                            }
                            function canBuyCallback(data)
                            {
                                canBuyFlag = data.canBuy;
                            }
                            function ajaxShow()
                            {
                                var category = <?php echo isset($category) ? $category : 0 ?>;
                                var gardenId = <?php echo isset($gardenId) ? $gardenId : 0 ?>;
                                ajaxLoader.get('<?php echo $this->createUrl('Items/DecoShow'); ?>&isAjaxShow=1&category='+category+'&gardenId='+gardenId,ajaxShowCallback);
                            }
                            function ajaxShowCallback(data)
                            {
                                $("#page").html(data);
                            }
                        </script>
                        <?php if ($welcomeAction == true) { ?>
                            <div class="b_text06 b_text06_1" style="position:absolute;bottom:-60px;right:-80px;">
                                <i class="text06pic"></i>
                                <div class="long">
                                    <span id="welcomeMessage"></span>
                                    <em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
                                </div>
                            </div>
                            <script language="javascript">
                                var WelcomeDecoName;
                                var WelcomeCount;
                                var WelcomePrice;
                                                                                                                                                                                                                                                                                                                                                                                        
                                <?php if (isset($decoList)) { ?>
                                    <?php foreach ($decoList as $decoId => $deco) { ?>
                                        <?php if ($decoId == 1000) { ?>
                                                        WelcomeDecoName = '<?php echo str_replace(substr($deco['item']->image, -4), '', $deco['item']->image); ?>';
                                                        WelcomeCount = <?php echo $deco['num']; ?>;
                                                        WelcomePrice = <?php echo $deco['item']->price; ?>;
                                            <?php break; ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </script>
                            <script language="javascript">
                                LoadAction.push(function(){
                                    $('div.guide_main_cover').show().css('z-index',100); 
                                });
                                var WelcomeGuide = {
                                    step : 0 ,
                                    zIndex : 101 ,
                                    nextStep : function(){
                                        this.step ++ ;
                                        switch( this.step ) {
                                            case 1 :
                                                $('.b_text06').css('z-index',this.zIndex++);
                                                $('.b_bg_04').append('<div class="b_text07 left" style="position:absolute; left:60px; top:130px;"><em class="star"></em><em class="new_hand"><span class="new_hand1"></span><span class="new_hand2"></span></em><em class="fangxiang new_left"></em></div>');
                                                $('.b_text05goon').remove();
                                                $('.new_hand').bind("click",function(){WelcomeGuide.nextStep();});
                                                $('.b_text07').css('z-index',this.zIndex++);
                                                var data = "<?php echo Yii::t('GuideMessage', 'message_61'); ?>";
                                                this.showTip(data);
                                                break ;
                                            case 2 :
                                                $('.new_hand').unbind();
                                                var params = {
                                                    "name":WelcomeDecoName,
                                                    "count":WelcomeCount,
                                                    "price":WelcomePrice,
                                                    "action":"newdeco",
                                                    "isFull":"false",
                                                    "accessLevel":66
                                                }
                                                $("#page").empty();
                                                NativeApi.callback(params); 
                                                break ;
                                            case 3 :
                                                $('.b_text06').css('z-index',this.zIndex++);
                                                $('.b_text05goon').remove();
                                                var data = "<?php echo Yii::t('GuideMessage', 'message_67'); ?>";
                                                this.showTip(data);
                                                break ;
                                            default:
                                                console.log(this.step);
                                            }
                                        } ,
                                        showTip: function(data){
                                            var self = this ;
                                            $('#welcomeMessage').empty();
                                            var elem = document.createElement('div');
                                            $(elem).attr('style','text-align: left;');
                                            $(elem).hide().html(data).fadeIn();
                                            $('#welcomeMessage').append(elem);
                                        }            
                                    }

                                    $(document).ready(function(){
                                        <?php if (isset($accessLevel) && $accessLevel == 67) { ?>
                                            WelcomeGuide.step = 2;
                                        <?php } ?>
                                            WelcomeGuide.nextStep();
                                            }) ;
                                        <?php } ?>
                        </script>
                        <script>
                                var a_list = document.getElementById("a_list");
                                var lists = a_list.getElementsByTagName("li");
                                a_list.style.width = lists.length*220+"px";
                                var isClicked = false;
                                var tag=0;
                                for(i = 0; i < lists.length ;i++){
                                    lists[i].getElementsByTagName("div")[0].onclick = (function(i){
                                        return function(){
                                            if(i!=tag){
                                                lists[tag].className ="";
                                                lists[tag].getElementsByTagName("p")[0].style.display = "none";
                                                isClicked = false;
                                            }
                                            isClicked = !isClicked; 
                                            lists[i].className =(isClicked?"hover01":"");
                                            lists[i].getElementsByTagName("p")[0].style.display = (isClicked?"block":"none");
                                            tag = i;
                                        }
                                    })(i);
                                }
                                document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
                                $(function(){
                                    hoverClass();
                                });
                        </script>
