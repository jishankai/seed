<div class="w480 h400 border1">

    <?php
    foreach ($categories as $i => $c) {
        if ($category == $i)
            echo "<b>$c</b>";
        else
            echo " <a href='" . $this->createUrl('items/resShow', array('category' => $i)) . "'>$c</a> ";
    }
    ?> 

    <div class="w480 h200" style="overflow-x:scroll;overflow-y:hidden;" >
        <div style="width:20000px; border:solid 1px blue; height:120px; margin:3px; padding:1px;">
            <?php if (isset($resList)) { ?>
                <?php foreach ($resList as $resId => $res) { ?>
                    <?php for ($n = 1; $n <= $res['pile']; $n++) { ?>
                        <div class="w200 border1 h90 fl ml10 mr10">
                            使用品ID:<?php echo $resId; ?><br /> 
                            使用品名称:<?php echo $res['item']->name; ?> <br />
                            使用品数量:<?php echo ITEM_MAXPILE; ?><br /><br />
                            种子ID:<input type="text" id ="seedId<?php echo $n; ?><?php echo $resId; ?>" name ="seedId"/><br>
                            <a id ="href<?php echo $n; ?><?php echo $resId; ?>" onclick="use(<?php echo $n; ?>,<?php echo $resId; ?>);return false" href="#">使用</a> <a id ="href<?php echo $n; ?><?php echo $resId; ?>" onclick="give(<?php echo $resId; ?>);return false" href="#">送礼</a> <a id ="href<?php echo $n; ?><?php echo $resId; ?>" onclick="sell(<?php echo $resId; ?>);return false" href="#">出售</a>
                        </div>
                    <?php } ?>
                    <?php if (isset($res['num']) && $res['num'] != 0) { ?>
                        <div class="w200 border1 h90 fl ml10 mr10">
                            使用品ID:<?php echo $resId; ?><br /> 
                            使用品名称:<?php echo $res['item']->name; ?> <br />
                            使用品数量:<?php echo $res['num']; ?><br /><br />
                            种子ID:<input type="text" id ="seedIdy<?php echo $resId; ?>" name ="seedId"/><br>
                            <a id ="hrefy<?php echo $resId; ?>" onclick="use('y',<?php echo $resId; ?>);return false" href="#">使用</a> <a id ="hrefy<?php echo $resId; ?>" onclick="give(<?php echo $resId; ?>);return false" href="#">送礼</a> <a id ="hrefy<?php echo $resId; ?>" onclick="sell(<?php echo $resId; ?>);return false" href="#">出售</a>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </div>
    </div>

</div>
<script type="text/javascript">
    function use(n,itemId)
    {   
        var seedId = document.getElementById("seedId"+n+itemId).value;
        
        if(seedId == '')
        {
            alert("你必须输入一个种子ID");
            return false;
        }
        
        addFlag = confirm("是否使用？");
        
        if(!addFlag)
        {
            return false;
        }

        ajaxLoader.get('<?php echo $this->createUrl('Items/Use'); ?>&userId='+seedId+'&itemId='+itemId,Common.refreshCurrentPage);

    }
    function give(itemId)
    {
        alert(itemId);
        selectFriend.url = '<?php echo $this->createUrl('/friend/friendSelect'); ?>';
        selectFriend.show( function(){
            CommonDialog.confirm( '确定赠送么？',function(){
                
                AjaxLoader.get( '<?php echo $this->createUrl('Items/Give'); ?>&friendId='+selectFriend.selectId+'&itemId='+itemId,Common.refreshCurrentPage);
               
            } );
         });

    }
    function sell(itemId)
    {           
        addFlag = confirm("是否卖掉？");
        
        if(!addFlag)
        {
            return false;
        }

        ajaxLoader.get('<?php echo $this->createUrl('Items/Sell'); ?>&itemId='+itemId,Common.refreshCurrentPage);

    }
</script>

