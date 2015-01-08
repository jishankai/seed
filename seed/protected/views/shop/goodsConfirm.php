
<div class="frame b_frame a_frame01">
    <a href="#" class="a_btn04" onclick="buyActionLock = false ; CommonDialog.close('shopGoodsBuyConfirm')"></a>
    <p class="a_bar1bg"><span class="a_bar1"><?php echo Yii::t('Shop','confirm title'); ?></span></p>
    <p class="tx01 a_color_blue"><?php echo Yii::t('Shop','are you sure to buy'); ?> </p>
    <div class="b_fsalediv" style="width:300px;">
        <p class="b_framesale">
            <img src="<?php echo $goods->getImage(); ?>">
        </p>
        <h4>X</h4>
        <input type="number" min="1" max="<?php echo $goods->getMaxBuyNum($this->playerId) ;?>" maxlength="4" name="points" id="shopGoodsNumber" onclick="goodsNumberChange();" onchange="goodsNumberChange();" value="1">
        <p class="num">
             <?php echo Yii::t('Shop','spend money'); ?>
            <?php if($goods->moneyType==1) {?>
            <em style="background:none;padding-left:0px;"><span id="currentPrice" class="jinzz">-<?php echo $goods->getPrice(); ?></span></em>
            <?php } else { ?>
            <em><span id="currentPrice">-<?php echo $goods->getPrice(); ?></span></em>
            <?php } ?>
        </p>
    </div>
    <div class="b_btnlist01"><a href="#" class="b_btn_08 a_btn_03" onclick="buyActionLock = false ; CommonDialog.close('shopGoodsBuyConfirm')">NO</a><a href="#" class="b_btn_07 a_btn_01" onclick="buyConfirm()">YES</a></div>
</div>



<script language="javascript">
var goodsPrice = <?php echo $goods->getPrice(); ?> ;
var goodsId = <?php echo $goods->goodsId; ?> ;
var buyActionLock = false ;
var goodsNumberChange = function(){
    var inputNum = $('#shopGoodsNumber').val() ;
    var num =  Math.max(0,Math.min( parseInt($('#shopGoodsNumber').attr('max')),inputNum?parseInt(inputNum):0 ));
    $('#shopGoodsNumber').val(num);
    console.log(parseInt($('#shopGoodsNumber').val()));
    $('#currentPrice').addClass('red').html(-num*goodsPrice);
}
var buyConfirm = function(){
    if( buyActionLock ) return ;
    buyActionLock = true ;
    setTimeout(function(){buyActionLock = false ;},1000);
    var num = parseInt( $('#shopGoodsNumber').val() );
    if( isNaN(num)||num<1 ) {
        $('#shopGoodsNumber').css('border','solid 2px red').focus();
    }
    else {
        AjaxLoader.get(CommonShop.confirmUrl+'&goodsId='+goodsId+'&num='+$('#shopGoodsNumber').val(),CommonShop.buySuccess);
    }
}
</script>
