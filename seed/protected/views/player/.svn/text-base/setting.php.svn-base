<style>
    .a_shezhi .a_bg_shezhi .shezhi_text li  {
        clear:both;
    }
    .a_shezhi .a_bg_shezhi .shezhi_text li h4  {
        width:500px; float:right; text-align:left;
    }

</style>

<div class="b_bg_04 a_shezhi">  
    <div style="position:static;" class="b_ico" onclick="playerSetting.close();"></div>
    <div class="pr01"><span class="a_bar1"><?php echo Yii::t('Setting', 'setting title'); ?></span></div>
    <div class="a_btn06"><?php echo $player->playerName; ?></div>
    <a href="#" class="a_btn_f a_btn13"><?php echo Yii::t('Setting', 'save settings'); ?></a>
    <a href="#" class="a_btn_f a_btn14"><?php echo Yii::t('Setting', 'reset settings'); ?></a>
    <p class="a_bg_shezhitop"></p>
    <div id="wrapper" style="position: absolute;left: 45px;top: 127px;height:513px;">
        <div class="a_bg_shezhi clearfix">	        
            <ul class="a_bb new_asz">
                <li>
                    <i class="a_szsy" style="margin-left:20px;"><?php echo Yii::t('Setting', 'music title'); ?></i>
                    <div class="szsy_bg" style="margin-left:36px;" id="musicFlag"><em class="off">off</em><em class="on">on</em><a class="left"></a></div>
                </li>
                <li>
                    <i class="a_szyy" style="margin-left:36px;"><?php echo Yii::t('Setting', 'sound title'); ?></i>
                    <div class="szsy_bg" style="margin-left:41px;" id="soundFlag"><em class="off">off</em><em class="on">on</em><a class="right"></a></div>
                </li>
            </ul>
            <ul class="shezhi_text">
                <li><span class="title"></span></li>
                <li>
                    <input type="checkbox" class="a_btn17" id="seedMoveableFlag" />
                    <h4><?php echo Yii::t('Setting', 'seedMoveable'); ?></h4>
                </li>
                <li>
                    <input type="checkbox" class="a_btn17" id="seedGrownFlag" />
                    <h4><?php echo Yii::t('Setting', 'seedGrown'); ?></h4>
                </li>
                <li>
                    <input type="checkbox" class="a_btn17" id="achievementFlag" />
                    <h4><?php echo Yii::t('Setting', 'achieveComplete'); ?></h4>
                </li>
                <li>
                    <input type="checkbox" class="a_btn17" id="newGiftFlag" />
                    <h4><?php echo Yii::t('Setting', 'newGift'); ?></h4>
                </li>
                <li></li>
            </ul>
            <div class="b_con_15">
                <a href="#" id="f_icon" class="b_ico_f"></a>
                <a href="#" id="t_icon" class="b_ico_t"></a>
                <a href="#" class="email">tanekko_support@sh.adways.net</a>
            </div>
            <div class="a_bg_shezhidi" style="z-index:-1"></div>
        </div>
        <!--设置主要内容-->
    </div>
</div>


<script type="text/javascript">
    var myScroll;
    function loaded() {
        myScroll = new iScroll("wrapper",{hScrollbar:false, vScrollbar:false});
    }
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
</script>

<script type="text/javascript">


    var playerSetting = {
        defaultData : <?php echo json_encode($defaultData); ?>,
        playerData : <?php echo json_encode($settingData); ?>,
        data : <?php echo json_encode($settingData); ?>,
        isChanged : false ,
        init : function(){
            var self = this ;
            if( !self.data ) {
                return ;
            }
            self.reset();
        },
        reset : function(){
            var self = this ;
            for( k in self.data ) {console.log(k);
                switch( k ) {
                    case 'musicFlag' :
                    case 'soundFlag' :
                        $('#'+k).children('a').attr('class',(self.data[k]?'left':'right'))
                        break ;
                    default : 
                        $('input#'+k).attr('checked',(self.data[k]?true:false) );
                }
            }
        } ,
        set : function( k,v,r ){
            var temp = k.split('.');
            switch (temp.length)
            {
                case 3:
                    this.data[temp[0]][temp[1]][temp[2]] = v ;
                    break ;
                case 2:
                    this.data[temp[0]][temp[1]] = v ;
                    break ;
                case 1:
                default:
                    this.data[k] = v ;
            }
            if(r)this.reset();
            console.log(this.data);
        },
        save : function(){
            var self = this ;
            //if( !isChanged ) return false ;
        
            NativeApi.delay(true);
            ajaxLoader.get('<?php echo $this->createUrl('player/settingApply'); ?>&data='+encodeURIComponent(JSON.stringify(playerSetting.data)),function(){
                NativeApi.callback('action','settings');
                for( k in playerSetting.data ) {
                    NativeApi.callback(k,playerSetting.data[k]);
                    playerSetting.playerData[k] = playerSetting.data[k];
                }
                NativeApi.close().doRequest();
            });
        
        },
        close:function(){
            NativeApi.delay(true);
            NativeApi.callback('action','settings');
            for( k in playerSetting.playerData ) {
                playerSetting.set(k,playerSetting.playerData[k]);
                NativeApi.callback(k,playerSetting.playerData[k]);
            }
            playerSetting.reset();
            NativeApi.close().doRequest();
        },
        restore : function(){
            var self = playerSetting ;
            for( k in self.defaultData )
                self.data[k] = self.defaultData[k] ;
            self.reset();
        }
    }






    $(document).ready(function(){
        $('.szsy_bg').click(function(){
            var element = $(this).children('a');
            var currentClass = element.attr('class');
            var testData = {};
            testData['action'] = 'settings';
            if( currentClass=='left' ) {
                element.attr('class','right');
                var flag = 0 ;
            }
            else {
                element.attr('class','left');
                var flag = 1 ;
            }
            var flagId = $(this).attr('id');
            playerSetting.set(flagId,flag);
            testData[flagId] = flag ;
            NativeApi.callback(testData);
        });

        $('a.a_btn_f.a_btn13').click( playerSetting.save );
        $('a.a_btn_f.a_btn14').click( playerSetting.restore );
        $('input.a_btn17').click(function(){
            playerSetting.set($(this).attr('id'),$(this).attr('checked')?1:0,1);
        });

        playerSetting.init();
    });



    var timeOut;
    function validateSns()
    {
        Common.ajaxLoadingDelay = 500000 ;
        timeOut = Common.ajaxTimeout;
        Common.ajaxTimeout = 1000000; 
        ajaxLoader.get('<?php echo $this->createUrl('Share/SetValidate'); ?>',validateSnsCallBack);
    }

    function validateSnsCallBack(data)
    {
        if(data.isFacebookBing == true)
        {
            $("#f_icon").removeClass("");
            $("#f_icon").addClass("b_ico_f on");
            $("#f_icon").attr("value", "");
            $("#f_icon").unbind();
        }else
        {
            $("#f_icon").removeClass("");
            $("#f_icon").addClass("b_ico_f");
            $("#f_icon").attr("value", data.facebookLoginUrl);
            $("#f_icon").bind("click",gotoBingFacebook);
        }
        
        if(data.isTwitterBing == true)
        {
            $("#t_icon").removeClass("");
            $("#t_icon").addClass("b_ico_t on");
            $("#t_icon").attr("value", "");
            $("#t_icon").unbind();
        }else
        {
            $("#t_icon").removeClass("");
            $("#t_icon").addClass("b_ico_t");
            $("#t_icon").attr("value", data.twitterLoginUrl);
            $("#t_icon").bind("click",gotoBingTwitter);
        }
    }
    
    function gotoBingTwitter()
    {
        var url = $("#t_icon").attr("value");
        CommonDialog.confirm('<?php echo Yii::t('View', 'bing twitter？'); ?>',function(){
            var params = {
                "action":"loginSns",
                "url":url
            }
            NativeApi.callback(params);
        });
    }
    
    function gotoBingFacebook()
    {
        var url = $("#f_icon").attr("value");
        CommonDialog.confirm('<?php echo Yii::t('View', 'bing facebook？'); ?>',function(){
            var params = {
                "action":"loginSns",
                "url":url
            }
            NativeApi.callback(params);
        });
    }

    LoadAction.push( function(){
        validateSns();
    });

</script>
