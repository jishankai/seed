
<div class="b_con_01 b_bg_01">
    <span class="a_bar1"><?php echo Yii::t('Shop','shop title');?></span>
    <i class="a_ico1 a_ico1_c1 <?php echo $category==2?'on':'';?>" cid='2' onclick="Common.goUrl('<?php echo $this->createUrl('/shop/index',array('category'=>2));?>');"><img src="themes/images/imga/ico37.png" alt=""/></i>
    <i class="a_ico1 a_ico1_c2 <?php echo $category==1?'on':'';?>" cid='1' onclick="Common.goUrl('<?php echo $this->createUrl('/shop/index',array('category'=>1));?>');"><img src="themes/images/imga/ico36.png" alt=""/></i>
    <i class="a_ico1 a_ico1_c3 <?php echo $category==6?'on':'';?>" cid='6' onclick="Common.goUrl('<?php echo $this->createUrl('/shop/index',array('category'=>6));?>');"><img src="themes/images/imgb/pic_58.png"><i class="a_ico2_2"></i></i>
    <i class="a_ico1 a_ico1_c4 <?php echo $category==4?'on':'';?>" cid='4'><img src="themes/img/img1.png"></i>
    <a href="#" class="a_btn04"></a>
    <div class="b_bg_02" >
        <div id="wrapper" style="width:882px; margin:0 auto; overflow:hidden;">
    	<ul class="b_list_01" id="a_list">
        <?php if( $category==4 ) {?>
            <li style="width:213px;margin-top:-10px;" class="" onclick="window.location.href='<?php echo $this->createUrl('ads/appdriverAds');?>'">
                <img src="themes/images/imgb/shop01.png" alt="">
                     
                <p style="display: none; ">
                    <a href="#" class="a_btn03"><img src="themes/images/imgb/pic_16.png"></a>
                    <a href="#" class="a_btn03"><img src="themes/images/imga/ico6.png"></a>
                </p>
            </li>
        <?php } ?>
        <?php foreach( $goodsList as $goodsId=>$goods ) {?>
            <?php if( $category==4 ) {?>
        	<li id="goodsData<?php echo $goodsId;?>" onclick="NativeApi.callback('purchase','<?php echo $goods->internalId;?>');">
            	<div>
                	<img src="<?php echo $goods->getImage(); ?>" alt="">
                    <span><img src="themes/images/imga/ico56_2.png" style="width:22px; height:23px;" /> <?php echo $goods->getPrice();?></span>
                    <b><?php echo $goods->getName(); ?></b>
                </div>
                <div>
                    <?php echo $goods->getDesc(); ?>
                </div>
                
            </li>
            <?php } else {?>
        	<li id="goodsData<?php echo $goodsId;?>">
            	<div class="clickArea">
                	<img src="<?php echo $goods->getImage(); ?>" alt="">
                    <span><img src="<?php echo ($goods->moneyType==1?'images/money.png':'themes/images/imgb/ico_7.png');?>" style="width:22px; height:23px;" /> <?php echo $goods->getPrice();?></span>
                    <b><?php echo $goods->getName(); ?></b>
                </div>
                <p class="dataArea">
                    <a href="#" class="a_btn03" onclick="CommonShop.buy(<?php echo $goodsId; ?>)"><img src="themes/images/imgb/pic_16.png"></a>
 
                    <?php if( $category==2 ) { ?>
                    <a href="#" class="a_btn03"><img src="themes/images/imga/ico6.png" onclick="CommonShop.send(<?php echo $goodsId; ?>)"></a>
                    <?php } ?>
                </p>
                <div>
                    <?php 
                        if( $category==2 ) {
                            for($i=1;$i<=6;$i++) echo ' <i>'.($goods->data['type']==$i?'<img src="themes/images/imgb/ico_'.$i.'.png">':'').'</i> ';
                        }
                     ?> 
                    <?php echo $goods->getDesc(); ?>
                </div>
                
            </li>
            <?php } ?>
        <?php } ?>
		</ul>
        </div>
	
    </div>
</div>




<script>
$(document).ready(function(){
    $('i.a_ico1').attr('onclick','').click(function(){
        var cid = $(this).attr('cid') ;
        if( !cid ) {
            NativeApi.callback('purchase','GOLD_1');
        }
        else {
            showShopTab( cid );
        }
    });
});

var showShopTab = function(c) {
    var url = '<?php echo $this->createUrl('/shop/index');?>&category='+c;
    $('i[cid=<?php echo $category;?>]').removeClass('on');
    $('i[cid='+c+']').addClass('on');
    ajaxLoader.get( url,function(data){
        $('#page').empty().append(data);
        $('a.a_btn04').click(function(){NativeApi.close();});
    } );
}
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

<?php if($this->actionType==REQUEST_TYPE_AJAX) {?>
    shopScroll();
<?php } else {?>
LoadAction.push( function(){
    shopScroll();
});
<?php } ?>

window.CommonShop = {
    buyUrl : '<?php echo $this->createUrl("/shop/buy");?>' ,
    sendUrl : '<?php echo $this->createUrl("/shop/send");?>' ,
    confirmUrl : '<?php echo $this->createUrl("/shop/confirm");?>' ,
    sendConfirmUrl : '<?php echo $this->createUrl("/shop/sendConfirm");?>' ,
    callBack : '' ,

    buy : function(id) {
        AjaxLoader.get( this.buyUrl+'&goodsId='+id,this.confirm );
    } ,

    send : function(id) {
        selectFriend.url = '<?php echo $this->createUrl('/friend/friendSelect');?>';
        selectFriend.show( function(){
            AjaxLoader.get( CommonShop.sendConfirmUrl+'&goodsId='+id+'&sendPlayerId='+selectFriend.selectId,CommonShop.buySuccess);
        });
    } ,
    
    confirm : function( data ) {
        switch (data.type) {
            case 1 :
                CommonDialog.create( 'shopGoodsBuyConfirm',data.message );
                break;
        }
    } ,
    
    buySuccess : function( data ){
        CommonDialog.close('shopGoodsBuyConfirm');
        CommonDialog.alertDisappear( data );
    }

}


</script>
