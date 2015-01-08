<style>
    .b_list_01 li{ margin-right:0; width:420px; height:206px; float:left; }
</style>
<div class="b_con_01 b_bg_01">
    <span class="a_bar1"><?php echo Yii::t('Item', 'item storage'); ?></span>
    <i id="goItemUrl1" class="a_ico1 a_ico1_c1 <?php echo $category == 1 ? 'on' : ''; ?>" onclick="goItemUrl(1)"><img src="themes/images/imga/ico37.png" alt=""/></i>
    <i id="goItemUrl2" class="a_ico1 a_ico1_c1 <?php echo $category == 0 ? 'on' : ''; ?>" onclick="goItemUrl(0)"><img src="themes/images/imga/ico36.png" alt=""/></i>
    <i id="goItemUrl3" class="a_ico1 a_ico1_c1 <?php echo $category == 3 ? 'on' : ''; ?>" onclick="goItemUrl(3)"><img src="themes/images/imga/img_02.png" alt=""/></i>
    <a href="#" onclick="NativeApi.close()" class="a_btn04"></a>
    <div class="b_list_02 pr" id="wrapper" style="height:440px;">
        <div id="scrollDemo">
            <ul class="b_list_01  clearfix" style="overflow:hidden;">
                <?php
                if (isset($itemList) && !empty($itemList)) {
                    $count = 0;
                    ?>
                    <?php
                    foreach ($itemList as $itemId => $item) {
                        $count++;
                        ?>
                        <li id ="li<?php echo $count; ?><?php echo $item['item']['id']; ?>" <?php if ($item['item']->category != 3) { ?> onclick="show_li(<?php echo $count; ?>,<?php echo $item['item']['id']; ?>);"<?php } ?>>
                            <div><?php $num = ($item['pile'] == 0) ? $item['num'] : ITEM_MAXPILE; ?>
                                <em id ="em<?php echo $count; ?><?php echo $item['item']['id']; ?>">X<?php echo $num ?></em>
                                <?php if ($item['item']->category == 3) { ?>
                                    <a id ="href<?php echo $count; ?><?php echo $item['item']['id']; ?>" onclick="use(<?php echo $count; ?>,<?php echo $item['item']['id']; ?>,<?php echo $item['item']->category; ?>);return false" href="#">
                                    <?php } ?>
                                    <img src="<?php echo $item['itemMeta']->getImagePath(); ?>" alt="">
                                    <?php if ($item['item']->category == 3) { ?>
                                    </a>
                                <?php } ?>
                                <?php if ($item['item']->category != 3) { ?>
                                    <span>
                                        <img src="themes/images/imgb/ico_7.png" alt="">
                                        <?php echo $item['item']->sellPrice; ?>
                                    </span>
                                <?php } else { ?>
                                    <span style="background:none">
                                    </span>
                                <?php } ?>
                                <b><?php echo $item['item']->name; ?></b>
                            </div>
                            <p>
                                <?php if ($item['item']->canSell == 1 && $item['item']->category != 3) { ?>
                                    <a id ="href<?php echo $count; ?><?php echo $item['item']['id']; ?>" onclick="sellItem(<?php echo $count; ?>,<?php echo $item['item']['id']; ?>,<?php echo $item['item']->category; ?>,<?php echo $num ?>);return false" href="#" class="a_btn03"><img src="themes/images/imga/ico3.png"></a>
                                <?php } ?>
                                <?php if ($item['item']->category == 1) { ?>
                                    <a id ="href<?php echo $count; ?><?php echo $item['item']['id']; ?>" onclick="give(<?php echo $count; ?>,<?php echo $item['item']['id']; ?>,<?php echo $item['item']->category; ?>);return false" href="#" class="a_btn03"><img src="themes/images/imga/ico6.png"></a>
                                <?php } ?>
                            </p>
                            <div>
                                <?php
                                if ($category == 1) {
                                    for ($i = 1; $i <= 6; $i++)
                                        echo ' <i>' . ($item['item']->type == $i ? '<img src="themes/images/imgb/ico_' . $i . '.png">' : '') . '</i> ';
                                }
                                ?>
                                <?php echo $item['item']->description; ?>
                            </div>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
        <b class="scroll_top"></b>
    </div>
</div>
<script type="text/javascript">
<?php if ($this->actionType == REQUEST_TYPE_AJAX) { ?>
        loaded();
<?php } else { ?>
        LoadAction.push(function(){ 
            loaded();
        });
<?php } ?>
    var myScroll;
    function loaded() {
        myScroll = new iScroll("wrapper",{hScrollbar:false, vScrollbar:false});
        document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
        document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
    }
    function goItemUrl(category)
    {   
        ajaxLoader.get('<?php echo $this->createUrl('Items/AjaxShow'); ?>&category='+category,goItemUrlCallback);
    }
    function goItemUrlCallback(data)
    {
        $("#page").html(data);
    }
    function show_li(n,itemId)
    {
        if($("#li"+n+itemId).hasClass("hover01"))
        {
            $(".hover01").removeClass("hover01");
            $("#li"+n+itemId).removeClass("hover01");
        }else
        {
            $(".hover01").removeClass("hover01");
            $("#li"+n+itemId).addClass("hover01");    
        }
    }
    function use(n,itemId,category)
    {   
        if(category==3)
        {
            ajaxLoader.get('<?php echo $this->createUrl('Items/Use'); ?>&n='+n+'&itemId='+itemId+'&category='+category,refreshNumCallback);
        }else
        {
            CommonDialog.confirm('<?php echo Yii::t('Item', 'dou you want to use it'); ?>',function(){         
                ajaxLoader.get('<?php echo $this->createUrl('Items/Use'); ?>&n='+n+'&itemId='+itemId+'&category='+category,refreshNumCallback);
            });
        }
    }
    function give(n,itemId,category)
    {
        selectFriend.url = '<?php echo $this->createUrl('/friend/friendSelect'); ?>';
        selectFriend.show( function(){
            ajaxLoader.get( '<?php echo $this->createUrl('Items/Give'); ?>&n='+n+'&friendId='+selectFriend.selectId+'&itemId='+itemId+'&category='+category,refreshNumCallback);
        });
    }
    function refreshNumCallback(data)
    {   
        var value = $("#em"+data.n+data.itemId).text();
        var numValue = parseInt(value.replace('X',''));
        if(data.num > 1&&data.num < numValue)
        {
            numValue = numValue - data.num;
        }
        else if(data.num > 1&&data.num >= numValue)
        {
            numValue = 0;
        }
        else
        {
            numValue = numValue - 1;
        }
        var newString = 'X'+numValue;
        $("#em"+data.n+data.itemId).text(newString);
        
        if(data.give == true)
        {   
            CommonDialog.alert('<?php echo Yii::t('Item', 'give item successful'); ?>',function(){
                if(numValue == 0)
                {
                    $("#li"+data.n+data.itemId).remove();
                    myScroll.refresh();
                }
            });
        }else if(data.sell == true)
        {
            if(numValue == 0)
            {
                $("#li"+data.n+data.itemId).remove();
                myScroll.refresh();
            }
        }
        else
        {
            window.getUseItemShow.data = data.useView;
            if(numValue == 0)
            {
                window.getUseItemShow.show();
                $("#li"+data.n+data.itemId).remove();
                myScroll.refresh();
            }else{
                window.getUseItemShow.show();   
            }
        }
    }
    function sellItemCallback()
    {   
        var data = window.sellItemShow.data;
        var sellPrice = window.sellItemShow.sellPrice;
        refreshNumCallback(data);
        CommonDialog.alertDisappear('<span class="yezi">'+sellPrice+'</span>');
    }
    function sellItem(n,itemId,category)
    {
        var value = $("#em"+n+itemId).text();
        var numall = parseInt(value.replace('X',''));
        window.sellItemShow.url = '<?php echo $this->createUrl('Items/sellItemShow') ?>&n='+n+'&itemId='+itemId+'&category='+category+'&numall='+numall;
        window.sellItemShow.show(sellItemCallback);
    }
</script>