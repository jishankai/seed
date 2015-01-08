<?php $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId); ?>
<div class="frame_02 b_frame a_frame01">
    <p class="a_bar1bg"><span class="a_bar1"><?php echo Yii::t('Item', 'window prompt'); ?></span></p>
    <p class="tx01 a_color_blue"><?php echo Yii::t('Item', 'your sales volume, please input'); ?></p>
    <div class="b_fsalediv" style="overflow:hidden;">
        <p class="b_framesale">
            <img src="<?php echo $itemMeta->getImagePath(); ?>">
        </p>
        <h4>X</h4>
        <input id="numerical" name="numerical" type="number" value ="1" onBlur="sumPrice()" min="1" max="<?php echo $numall; ?>">
        <a href="#" class="btn" onclick="getMax()"></a>
        <p class="num">
            <?php echo Yii::t('Item', 'get'); ?>: <em id="sumPrice"><?php echo '+' . $itemMeta->getSellPrice(); ?></em>
        </p>
    </div>
    <div class="b_btnlist01"><a href="#" onclick="CommonDialog.close('itemSellContainer');" class="b_btn_08 a_btn_03"><?php echo Yii::t('Item', 'give up to buy'); ?></a><a href="#" class="b_btn_07 a_btn_01" onclick="sellItems();"><?php echo Yii::t('Item', 'ok'); ?></a></div>
</div>
<script>
    var numerical = $('#numerical').attr('value');
    var numall = <?php echo $numall; ?>;
    var itemId = <?php echo $itemId; ?>;
    var n = <?php echo $n; ?>;
    var category = <?php echo $category; ?>;
    var name = <?php echo $itemMeta->getName(); ?>;
    
    function getMax()
    {
        $('#numerical').attr('value',numall);
        sumPrice();
    }
    
    function sumPrice()
    {   
        numerical = $('#numerical').attr('value');
        var re = new RegExp("^\\d+$");
        var bol = re.test(numerical);
        
        if(numerical<0||!bol)
        {
            $('#numerical').attr('value',0);
            $('#sumPrice').html('0');
            return false;
        }

        if(numerical > numall)
        {
            numerical = numall;
            $('#numerical').attr('value',numall);
        }

        var sumPrice = parseInt(numerical) * parseInt(<?php echo $itemMeta->getSellPrice(); ?>);

        if(sumPrice>0)
        {
            $('#sumPrice').html('+'+sumPrice);
        }else
        {
            $('#sumPrice').html('0');   
        }
    }
    
    function sellItems() {
        numerical = $('#numerical').attr('value');
        if(numerical > 0)
        {
            AjaxLoader.get('<?php echo $this->createUrl('items/sell'); ?>'+"&n="+n+"&itemId="+itemId+"&category="+category+"&num="+$('#numerical').attr('value'),sellItemsCallBack);
        }else
        {
            return false;
        }
    }
    
    function sellItemsCallBack(data)
    {
        window.sellItemShow.sellPrice = $('#sumPrice').text();
        window.sellItemShow.data = data;
        window.sellItemShow.over();
    }
    
</script>