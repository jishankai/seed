<div class="b_con_01 b_bg_03" id="b_bg">
    <div class="pr01">
        <span class="a_bar1">
            <span id="gardenTitle"><?php echo Yii::t('Garden', 'player gardenList'); ?></span>
            <span id="setloveTitle" style="display:none"><?php echo Yii::t('Garden', 'set favorite garden'); ?></span>
        </span>
    </div>
    <span id="loveGardenButton" <?php if (!$showFlag) { ?> style="display:none" <?php } ?>>    
        <div class="a_bar3"><a onclick="showInput();return false;" href="#"><img src="themes/images/imga/ico11.png"></a></div>
        <div class="a_bar2" style="display:none">
            <a id ="href_cancel" onclick="cancelInput();return false;" href="#"></a>
            <a id ="href_ok" onclick="setLove();return false;" href="#"></a>
        </div>
    </span>
    <a href="#" class="a_btn04"></a>
    <div class="a_scroll pr b_gadem" style="position:absolute;top:120px; left:35px;padding-top:1px; ">
        <div class="scroll_top"></div>
        <div class="a_list1 a_list1border h415" style="padding:0;">
            <div id="wrapper" style="height:440px;">
                <ul id="scrolldemo">
                    <?php foreach ($allGardens as $garden) { ?>
                        <li onclick="selectMoveGarden(<?php echo $garden->gardenId; ?>)">
                            <h3 id="h3_<?php echo $garden->gardenId; ?>" value="<?php echo $garden->gardenId; ?>">
                                <a class ="gardenHref"onclick="selectGarden(<?php echo $garden->gardenId; ?>,<?php echo $garden->playerId; ?>,<?php echo $garden->gardenSign; ?>);" href="#">
                                    <?php $backGround = (int) $garden->backGround - MIN_BACKGROUDID + 1; ?>
                                    <span class="<?php echo $backGround < GARDEN_MAXGARDENSIGN ? 'num0' . $backGround : 'num' . $backGround; ?>" ><img src="images/smallItem/<?php echo $garden->gardenSign; ?>.png"/></span>
                                </a>
                                <span><img src="themes/images/imga/ico10.png" /><?php echo $garden->decoExtraGrow; ?></span>
                                <input type="radio" id ="chk_<?php echo $garden->gardenId; ?>" name="radio" value="<?php echo $garden->gardenId; ?>" <?php if ($garden->favouriteFlag == 1) { ?>checked="true"<?php } ?> style="display:none"/><br />
                                <i id ="i_<?php echo $garden->gardenId; ?>"></i>
                                <?php
                                if ($garden->favouriteFlag == 1) {
                                    echo "<i class='a_ico4' id='a_ico4'></i>";
                                }
                                ?>
                                <?php if ($garden->gardenSign == 1) { ?>
                                    <i class="b_ico_mouth"></i>
                                <?php } ?>
                            </h3>
                            <ul class="b_imgli01 clearfix" id="ulGraden<?php echo $garden->gardenId; ?>">
                                <?php foreach ($garden->seedArrayList as $seedId) { ?>
                                    <?php if (!empty($seedId)) { ?>
                                        <?php if (isset($isVisual) && $isVisual == true) { ?>
                                            <?php $seed = VisualFriend::getSeedObject($seedId); ?>
                                        <?php } else { ?>
                                            <?php $seed = Yii::app()->objectLoader->load('Seed', $seedId); ?>
                                        <?php } ?>
                                        <li id ="liSeed<?php echo $seed->seedId; ?>" value="<?php echo $seed->seedId; ?>" style="width: 65px;margin-left: 15px;">
                                            <a class ="seedHref"id ="showSeed<?php echo $seed->seedId; ?>" onclick="seedInfoShow(<?php echo $seed->seedId; ?>)" href="#">
                                                <?php if ($showFlag) { ?>
                                                    <input type="radio" id ="chkSeed_<?php echo $seed->seedId; ?>" name="radioSeed" class="radioSeed" value="<?php echo $seed->seedId; ?>" style="display:none"/><br />
                                                <?php } else { ?>
                                                    <input type="radio" style="display:none"/><br />
                                                <?php } ?>
                                                <strong id="seedImageArea<?php echo $seed->seedId; ?>" class="fl w80" style="margin-left: 8px;margin-top: 10px;" onclick="selectLoveSeed(<?php echo $seed->seedId; ?>)">
                                                </strong>
                                            </a>
                                            <?php if ($seed->favouriteFlag == 1) { ?>
                                                <i class="a_ico5" id='a_ico5'></i>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <span id="buyNewGarden">
                        <?php if ($showFlag) { ?>
                            <?php if (isset($gardenPrice)) { ?>
                                <?php if ($gardenPrice['gardenCount'] < GARDEN_MAXGARDENSIGN) { ?>
                                    <li class="empty ac">
                                        <?php if ($gardenPrice['nowlevel'] < $gardenPrice['nextlevel']) { ?>      
                                            <a class="a_btn1 a_btn02 mt20" href="#" disabled="disabled"><img src="themes/images/imga/ico2.png"><?php echo 'LEVEL' . $gardenPrice['nextlevel']; ?></a>
                                        <?php } ?>
                                        <?php if ($gardenPrice['nowlevel'] >= $gardenPrice['nextlevel'] && $gardenPrice['gold'] < $gardenPrice['price']) { ?>
                                            <a class="a_btn1 a_btn01 mt20" href="#" onclick="ajaxLoader.get('<?php echo $this->createUrl('garden/AjaxGPrice'); ?>',addGarden)" disabled="disabled" style="color:red"><img src="themes/images/imgb/pic_57.png" alt=""><?php echo $gardenPrice['price']; ?> <front color="red"></a>
                                        <?php } ?>
                                        <?php if ($gardenPrice['nowlevel'] >= $gardenPrice['nextlevel'] && $gardenPrice['gold'] >= $gardenPrice['price']) { ?>
                                            <a class="a_btn1 a_btn01 mt20" href="#" onclick="ajaxLoader.get('<?php echo $this->createUrl('garden/AjaxGPrice'); ?>',addGarden)"><img src="themes/images/imgb/pic_57.png" alt=""><?php echo $gardenPrice['price']; ?></a>
                                        <?php } ?>
                                    </li>
                                <?php } else { ?>
                                    </br></br></br>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </span>
                    <div style="height:38px;"></div>    
                </ul>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var WelcomeSeedId;
    var WelcomeGardenId;
    $(document).ready(function(){
<?php if (isset($allGardens)) { ?>
    <?php $WelcomeSeedCount = 0; ?>
    <?php foreach ($allGardens as $garden) { ?>
        <?php foreach ($garden->seedArrayList as $seedId) { ?>
            <?php $WelcomeSeedCount++; ?>
            <?php if (empty($seedId)) continue; ?>
            <?php if (isset($isVisual) && $isVisual == true) { ?>
                <?php $seed = VisualFriend::getSeedObject($seedId); ?>
            <?php } else { ?>
                <?php $seed = Yii::app()->objectLoader->load('Seed', $seedId); ?>
            <?php } ?>
            <?php if ($WelcomeSeedCount == 1) { ?>
                                    WelcomeSeedId = <?php echo $seed->seedId; ?>;
            <?php } ?>
                                SeedUnit('seedImageArea<?php echo $seed->seedId; ?>',<?php echo $seed->getDisplayData(); ?>,0.55);
        <?php } ?>
                        WelcomeGardenId = <?php echo $garden->gardenId; ?>;
    <?php } ?>
<?php } ?>
    });
    
    //控制其它种子信息弹窗或访问花园链接是否失效
    var canDoFlag=1;
    var canDoFlag2=1;
    var loveSeedId = 0;
    var fromGarden = 0;
    var fromSeed = 0;
    
    var myScroll;
    function loaded() {
        myScroll = new iScroll("wrapper",{checkDOMChanges: true,hScrollbar:false, vScrollbar:false});
    }
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);

    var shakeSeed;
    var shakeSeedId = <?php if (isset($shakeSeedId)) { ?><?php echo $shakeSeedId; ?><?php } else { ?><?php echo 0; ?><?php } ?>;
    $(document).ready(function(){
        function servicechange(obj){
            $(obj).children('input').attr("checked","checked");
        };
        $("#i_"+<?php echo $selectGardenId; ?>).addClass("a_ico3");
    });
    function seedInfoShow(seedId)
    {
        if(canDoFlag==1&&canDoFlag2==1)
        {
            SeedAction.showInfo(seedId);
        }
    }
    function selectLoveSeed(seedId)
    {
        if(canDoFlag2 == 0)
        {
            var radio = document.getElementById("chkSeed_"+seedId);
            radio.checked = "checked"; 
            loveSeedId = seedId;
            if($("#a_ico5").length>0)
            {
                $("#a_ico5").remove();
            }
            $("#liSeed"+seedId).append("<i class='a_ico5' id='a_ico5'></i>");
        }
    }
    function moveSeed()
    {
        if(shakeSeedId!=0)
        {
            $('#loveGardenButton').hide();
            canDoFlag=0;
            var radio = document.getElementById("chkSeed_"+shakeSeedId);
            radio.checked = "checked";
            if(shakeSeed)
            {
                shakeSeed.stop();
            }
            shakeSeed = new ShakeObject("#seedImageArea"+shakeSeedId+">.seedContainer");
        }
    }
    function moveSeedToGarden(toGarden)
    {
        var seedId = shakeSeedId;      
        if(seedId == 0)
        {
            return false;
        }
        ajaxLoader.get('<?php echo $this->createUrl('Garden/MoveSeed'); ?>&seedId='+seedId+'&toGarden='+toGarden,moveSeedToGardenCallback);
    }
    function moveSeedToGardenCallback(data)
    {
        var seedArea = $("#liSeed"+data.seedId);
        $("#ulGraden"+data.toGarden).append(seedArea.clone());
        seedArea.remove();
        shakeSeed.stop();
        shakeSeedId = 0 ;
        var code_Values = document.getElementsByTagName("input");
        for(i = 0;i < code_Values.length;i++){ 
            if(code_Values[i].name=="radioSeed")
            {
                if(code_Values[i].checked == true)
                {
                    code_Values[i].checked = false;
                }
            }
        }
        $('#loveGardenButton').show();
        canDoFlag=1;
        
<?php if ($welcomeAction == true) { ?>
            WelcomeGuide.step = 8;
            WelcomeGuide.nextStep();
<?php } ?>
    
        ajaxLoader.get('<?php echo $this->createUrl('Garden/refreshCreate'); ?>',refreshCreateCallback);
    }
    function setLove()
    {
        var code_Values = document.getElementsByTagName("input");
        var value = 0;
        for(i = 0;i < code_Values.length;i++){ 
            if(code_Values[i].type == "radio"&&code_Values[i].name=="radio") 
            { 
                if(code_Values[i].checked == true)
                {
                    value = code_Values[i].value;
                }
            }
        }
        
        if(value > 0)
        {
            ajaxLoader.get('<?php echo $this->createUrl('Garden/Favourite'); ?>&gardenId='+value+'&loveSeedId='+loveSeedId,setLoveCallback);
        }
    }
    function setLoveCallback(data)
    {
        var gardenArea = $("#a_ico4");
        $("#h3_"+data.gardenId).append(gardenArea.clone());
        gardenArea.remove();
        
        if(data.loveSeedId!=0)
        {
            if($("#a_ico5").length>0)
            {
                $("#a_ico5").remove();
            }
            $("#liSeed"+data.loveSeedId).append("<i class='a_ico5' id='a_ico5'></i>");
            var radio = document.getElementById("chkSeed_"+data.loveSeedId);
            radio.checked = false; 
        }
        
        hiddenInput();
        
        ajaxLoader.get('<?php echo $this->createUrl('Garden/refreshCreate'); ?>',refreshCreateCallback);
    }
        
    function addGarden(data)
    {
        if(data.gardenCount >= 10)
        {
            return false;
        }
        CommonDialog.confirm('<?php echo Yii::t('Garden', 'will you want buy a garden?'); ?>',function(){
            ajaxLoader.get('<?php echo $this->createUrl('Garden/Create'); ?>',addGardenCallback);
        });           
    }
    function addGardenCallback(data)
    {
        $("#buyNewGarden").remove();
        $("#scrolldemo").append(data.newGardenHtml);
        $("#scrolldemo").append(data.newBuyGardenHtml);
        CommonDialog.alertDisappear('<?php echo Yii::t('Garden', 'garden buy successful'); ?>');
<?php if ($welcomeAction == true) { ?>
            WelcomeGuide.step = 3;
            WelcomeGuide.nextStep();
<?php } ?>
    }
    function getReward($index)
    {   
        CommonDialog.confirm('<?php echo Yii::t('Garden', 'will you want get a reward?'); ?>',function(){
            ajaxLoader.get('<?php echo $this->createUrl('Garden/GetReward'); ?>&index='+$index,common.refreshCurrentPage());
        });
    }
    function delSeedCallback(seedId)
    {   
        eval("if(window.growingSeed"+seedId+")growingSeed"+seedId+".clear();")
        $("#liSeed"+seedId).remove();
        ajaxLoader.get('<?php echo $this->createUrl('Garden/refreshCreate'); ?>',refreshCreateCallback);
    }
    function refreshCreateCallback(data)
    {
        $("#buyNewGarden").html(data.newBuyGardenHtml);
    }
    function setSeedLoveCallback(seedId)
    {
        $("#a_ico5").remove();
        $("#liSeed"+seedId).append("<i class='a_ico5' id='a_ico5'></i>");
    }
    function showInput()
    {   
        canDoFlag2 = 0;
        $(".a_bar3").hide();
        $(".a_bar2").show();
        $(".a_ico3").removeClass("a_ico3");
        $("#buyNewGarden").hide();
        $("#setloveTitle").show();
        $("#gardenTitle").hide();
        fromGarden = $("#a_ico4").parent().attr('value');
        fromSeed = $("#a_ico5").parent().attr('value');
    }
    function cancelInput()
    {
        if($(".a_ico4").length>0)
        {
            $(".a_ico4").remove();
        }
        $("#h3_"+fromGarden).append("<i class='a_ico4' id='a_ico4'></i>");
        var radio = document.getElementById("chk_"+fromGarden);
        radio.checked = true; 
        
        if($(".a_ico5").length>0)
        {
            $(".a_ico5").remove();
        }
        $("#liSeed"+fromSeed).append("<i class='a_ico5' id='a_ico5'></i>");
        if(loveSeedId!=0)
        {
            var radio = document.getElementById("chkSeed_"+loveSeedId);
            radio.checked = false;
        }
        
        hiddenInput();
    }
    function hiddenInput()
    { 
        canDoFlag2 = 1;
        $(".a_bar2").hide();
        $(".a_bar3").show();
        $(".a_ico3").removeClass("a_ico3");
        $("#buyNewGarden").show();
        $("#setloveTitle").hide();
        $("#gardenTitle").show();
    }
    function selectMoveGarden(gardenId)
    {
        if(canDoFlag2 == 0)
        {
            return false;
        }
        
        var countSeed=0;
        var code_Values = document.getElementsByTagName("input");
        for(i = 0;i < code_Values.length;i++){ 
            if(code_Values[i].type == "radio"&&code_Values[i].name=="radioSeed")
            {
                if(code_Values[i].checked == true)
                {
                    countSeed=countSeed+1;
                }
            }
        }
        
        if(countSeed>0)
        {
            moveSeedToGarden(gardenId);
        }
    }
    function selectGarden(gardenId,playerId,gardenSign)
    {
        if(canDoFlag==1)
        {
            if(canDoFlag2 == 0)
            {
                if($(".a_ico4").length>0)
                {
                    $(".a_ico4").remove();
                }
                $("#h3_"+gardenId).append("<i class='a_ico4' id='a_ico4'></i>");
                var radio = document.getElementById("chk_"+gardenId);
                radio.checked = true; 
            }
            else
            {   
<?php if (isset($isVisual) && $isVisual == true) { ?>
                    NativeApi.callback({'action':'visualVisit','playerId':playerId,'gardenId':gardenId});
<?php } else { ?>
                    NativeApi.callback({'action':'visit','playerId':playerId,'gardenSign':gardenSign});
<?php } ?>
            }
        }
    }
    window.onload=function(){
        moveSeed();
    }
</script>
</div>
<?php if ($welcomeAction == true) { ?> 
    <div class="b_text06 b_text06_1" style="position:absolute;bottom:-60px;right:-110px;">
        <i class="text06pic" style="left:50px;"></i>
        <div class="height">
            <div id="text">
            </div>
            <em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
        </div>
    </div>
    <script type="text/javascript">
        //        function shupai(eid) {
        //            var p = document.getElementById(eid);
        //            var str=p.innerHTML.replace(/<[^>]+>/gi,"");
        //            var arr = str.replace(/(.{1,13})/g,"<span>$1</span>");  //把10改成需要的字数
        //            p.innerHTML = arr;
        //        }
        window.onload = function() {shupai("text");}
        LoadAction.push(function(){
            $('div.guide_main_cover').show().css('z-index',100); 
        });
        var WelcomeGuide = {
            step : 0,
            zIndex : 101 ,
            nextStep : function(){
                this.step ++ ;
                switch( this.step ) {
                    case 1 :
                        $('.b_text06').css('z-index',this.zIndex++);
                        $('.b_text05goon').hide();
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:300px; top:340px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:370px; top:340px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:440px; top:340px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:510px; top:340px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:580px; top:340px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:60px; top:320px;"><em class="fangxiang new_right"></em></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:430px; top:340px;" onclick="WelcomeGuide.nextStep()"><em class="new_hand"><span class="new_hand1"></span><span class="new_hand2"></span></em></div>');
                        $('.b_text07').css('z-index',this.zIndex++);
                        var data = "<?php echo Yii::t('GuideMessage', 'message_121'); ?>";
                        this.showTip(data);
                        //                        shupai("text");
                        break ;
                    case 2 :
                        $('.b_text07').remove();
                        this.zIndex = 3000;
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:430px; top:320px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:480px; top:320px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:530px; top:320px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:200px; top:310px;"><em class="fangxiang new_right"></em></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:480px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="new_hand"><span class="new_hand1"></span><span class="new_hand2"></span></em></div>');
                        $('.b_text07').css('z-index',this.zIndex++);
                        ajaxLoader.get('<?php echo $this->createUrl('garden/AjaxGPrice'); ?>',addGarden);
                        break ;
                    case 3 :
                        $('.b_text07').remove();
                        $('.common_diaglog').remove();
                        ajaxLoader.get('<?php echo $this->createUrl('Garden/Create'); ?>',addGardenCallback);
                        break ;
                    case 4 :
                        $('.b_text07').remove();
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:50px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:50px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:90px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:90px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:160px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:230px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:300px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:370px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:440px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:510px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:580px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:300px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:370px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:440px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:510px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:580px; top:330px;"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:650px; top:330px;"><em class="star"></div>');
                        $('.b_ico01').css('z-index',this.zIndex++);
                        $('.b_text07').css('z-index',this.zIndex++);
                        $('.b_text05goon').show();
                        $('.b_text06').css('z-index',this.zIndex++);
                        $('.b_text06').bind("click",function(){WelcomeGuide.nextStep();});
                        var data = "<?php echo Yii::t('GuideMessage', 'message_123'); ?>";
                        this.showTip(data);
                        //                        shupai("text");
                        break ;
                    case 5 :
                        $('.b_ico01').remove();
                        $('.b_text07').remove();
                        $('.b_text06').css('z-index',this.zIndex++);
                        $('.b_text06').unbind();
                        $('.b_text05goon').hide();
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:165px; top:185px;"><em class="star"></em><em class="new_hand"><span class="new_hand1"></span><span class="new_hand2"></span></em><em class="fangxiang new_left"></em></div>');
                        $('.new_hand').bind("click",function(){WelcomeGuide.nextStep();});
                        $('.b_text07').css('z-index',this.zIndex++);
                        var data = "<?php echo Yii::t('GuideMessage', 'message_124'); ?>";
                        this.showTip(data);
                        //                        shupai("text");
                        break ;
                    case 6 :
                        $('.b_text07').remove();
                        $('.b_text06').css('z-index',2000);
                        this.zIndex = 2001;
                        $('div.guide_main_cover').css('z-index',this.zIndex++);
                        seedInfoShow(WelcomeSeedId);
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:485px; top:430px;"><em class="star"></em><em class="new_hand"><span class="new_hand1"></span><span class="new_hand2"></span></em><em class="fangxiang new_left"></em></div>');
                        $('.new_hand').bind("click",function(){WelcomeGuide.nextStep();});
                        $('.b_text07').css('z-index',this.zIndex++);
                        var data = "<?php echo Yii::t('GuideMessage', 'message_125'); ?>";
                        this.showTip(data);
                        //                        shupai("text");
                        break ;
                    case 7 :
                        $('.b_text07').remove();
                        moveCurrentSeed();
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:50px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:50px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:90px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:90px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:160px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:230px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:300px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:370px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:440px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:510px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:580px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:300px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:370px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:440px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:510px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:580px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:650px; top:330px;" onclick="WelcomeGuide.nextStep()"><em class="star"></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:225px; top:230px;"><em class="fangxiang new_bottom"></em></div>');
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:380px; top:380px;" onclick="WelcomeGuide.nextStep()"><em class="new_hand"><span class="new_hand1"></span><span class="new_hand2"></span></em></div>');
                        $('#b_bg').append('<div id="b_text07_2" class="b_text07 left" style="position:absolute; top:180px; left:165px;"><em class="star"></em></div>');
                        $('.b_ico01').css('z-index',this.zIndex++);
                        $('.b_text07').css('z-index',this.zIndex++);
                        $('.new_hand').bind("click",function(){WelcomeGuide.nextStep();});
                        $('.b_text07').css('z-index',this.zIndex++);
                        var data = "<?php echo Yii::t('GuideMessage', 'message_126'); ?>";
                        this.showTip(data);
                        //                        shupai("text");
                        break ;
                    case 8 :
                        if($('#newGardenId').length>0){
                            WelcomeGardenId = $('#newGardenId').val();
                        }
                        selectMoveGarden(WelcomeGardenId);
                        break ;
                    case 9 :
                        $('.b_text07').remove();
                        $('.b_ico01').remove();
                        $('#b_bg').append('<div class="b_text07 left" style="position:absolute; left:60px; top:340px;"><em class="star"></em><em class="new_hand"><span class="new_hand1"></span><span class="new_hand2"></span></em><em class="fangxiang new_left"></em></div>');
                        $('.new_hand').bind("click",function(){WelcomeGuide.nextStep();});
                        $('.b_text06').css('z-index',this.zIndex++);
                        $('.b_text05goon').hide();
                        $('.b_text07').css('z-index',this.zIndex++);
                        var data = "<?php echo Yii::t('GuideMessage', 'message_128'); ?>";
                        this.showTip(data);
                        //                        shupai("text");
                        break ;
                    case 10 :
                        NativeApi.delay(true);
                        ajaxLoader.get('<?php echo $this->createUrl('guide/saveStatus'); ?>',function(){
                            //("#page").empty();
                            NativeApi.callback({'action':'visit','playerId':<?php echo $this->playerId; ?>,'gardenSign':2,'accessLevel':123}).doRequest();
                        });
                        //$('.new_hand').remove();
                        break ;
                    default:
                        console.log(this.step);
                    }
                } , 
                showTip: function(data){
                    var self = this ;
                    $('#text').empty();
                    var elem = document.createElement('div');
                    $(elem).attr('style','text-align: left;');
                    $(elem).hide().html(data).fadeIn();
                    $('#text').append(elem);
                }            
            }

            $(document).ready(function(){
    <?php if (isset($accessLevel) && $accessLevel == 121) { ?>
                    WelcomeGuide.step = 3;
    <?php } ?>
    <?php if (isset($accessLevel) && $accessLevel == 122) { ?>
                    WelcomeGuide.step = 8;
    <?php } ?>
                WelcomeGuide.nextStep();
            }) ;
<?php } ?>
</script>