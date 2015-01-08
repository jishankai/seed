<div class="main">
<div class="ac">
确实要花费“<span id="currentPrice"><?php echo $goods->getPrice(); ?></span>” <?php echo $goods->getMoneyName(); ?> 来购买 “<?php echo $goods->getName(); ?>” 送给你的好友吗？
<br />
<div style="display:none;">
数量 ：
<input type="number" name="points" min="1" max="9999" value="1" class="w50" maxlength="4" id="shopGoodsNumber" onclick="goodsNumberChange();" onchange="goodsNumberChange();" /> 
好友PlayerId：<input type="text" class="w50" id="sendPlayerId" /> 
</div>
</div>

<div class="ac mt10 lh20"> 
    <input type="button"  value=" 我送！我快乐！ " onclick="sendConfirm()"> 
    <input type="button" value=" 点错了，不给！ " onclick="CommonDialog.close()" /> </div>

</div>
<script language="javascript">
var goodsPrice = <?php echo $goods->getPrice(); ?> ;
var goodsId = <?php echo $goods->goodsId; ?> ;
var goodsNumberChange = function(){
    var num =  Math.min( parseInt($('#shopGoodsNumber').attr('max')),parseInt( $('#shopGoodsNumber').val() ) );
    $('#shopGoodsNumber').val(num);
    $('#currentPrice').addClass('red').html(num*goodsPrice);
}
var sendConfirm = function(){
    //var playerId = parseInt($('#sendPlayerId').val()) ;
    var playerId = selectFriend.selectId ;
    if( isNaN(playerId)||playerId<1 ) {
        //CommonDialog.alert('请输入要赠送的玩家Id');
        $('#sendPlayerId').css('border','solid 2px red').focus();
    }
    else {
        var num = parseInt( $('#shopGoodsNumber').val() );
        if( isNaN(num)||num<1 ) {
            $('#shopGoodsNumber').css('border','solid 2px red').focus();
        }
        else {
            ajaxLoader.get(CommonShop.sendConfirmUrl+'&goodsId='+goodsId+'&num='+$('#shopGoodsNumber').val()+'&sendPlayerId='+playerId,CommonShop.buySuccess);
        }
    }
}
</script>
