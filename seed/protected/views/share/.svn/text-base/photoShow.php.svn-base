<script type="text/javascript">
    (function (jQuery) {
        jQuery.fn.inputLabel = function(text,opts) {
            o = jQuery.extend({ color: "#999", e:"focus", force : false, keep : true}, opts || {});
            var clearInput = function (e) {
                var target = jQuery(e.target);
                var value = jQuery.trim(target.val());
                if (e.type == e.data.obj.e && value == e.data.obj.innerText) {
                    jQuery(target).css("color", "").val("");
                    if (!e.data.obj.keep) {
                        jQuery(target).unbind(e.data.obj.e+" blur",clearInput);
                    }
                } else if (e.type == "blur" && value == "" && e.data.obj.keep) {
                    jQuery(this).css("color", e.data.obj.color).val(e.data.obj.innerText);
                }
            };
            return this.each(function () {
                o.innerText = (text || false);
                if (!o.innerText) {
                    var id = jQuery(this).attr("id");
                    o.innerText = jQuery(this).parents("form").find("label[for=" + id + "]").hide().text();
                }
                else 
                    if (typeof o.innerText != "string") {
                        o.innerText = jQuery(o.innerText).text();
                    }
                o.innerText = jQuery.trim(o.innerText);
                if (o.force || jQuery(this).val() == "") {
                    jQuery(this).css("color", o.color).val(o.innerText);
                }
                jQuery(this).bind(o.e+" blur",{obj:o},clearInput);
			
            });
        };
    })(jQuery);
    $(function () {
        $("#shareMessage").inputLabel("<?php echo $shareMessage; ?>");
    });
</script>
<div class="frame b_frame b_frame12">
    <span class="a_bar1 ac" style="top:50px; left: 180px;"><?php echo Yii::t('View', 'share window'); ?></span>
    <a href="#" class="a_btn04"></a>
    <div class="b_frame11">
        <textarea class="b_con_09 " name="shareMessage" id="shareMessage" cols="38" rows="5" onBlur="showlcontent()" maxlength="140" ></textarea>
        <?php if ($shareImageName != '') { ?>
            <em></em>
        <?php } ?>
    </div>
    <?php if ($shareImageName != '') { ?>
        <div class="picsure">
            <img src="photoUpload/<?php echo 'small_' . $shareImageName; ?>.png" /><input type="hidden" id="shareImageName" name="shareImageName" value="<?php echo 'middle_' . $shareImageName; ?>.png" />
        </div>
    <?php } else { ?>
        <input type="hidden" id="shareImageName" name="shareImageName" value=""/>
    <?php } ?>
    <i class="numsure" style="float: right; margin-right: 40px;">
        <span id="lcontent">0</span>/
        <span id="maxNumArea">140</span>
    </i>
    <p class="zhuansure">
        <?php if ($isFacebookBing == true) { ?>
            <a id="f_image" href="#" onclick="f_select()"><img src="themes/images/imgb/pic_51.png" /><span class="yes" id="f_yes"></span></a>
        <?php } else { ?>
            <a href="#"><img src="themes/images/imgb/pic_51_02.png" /></a>
        <?php } ?>
        <?php if ($isTwitterBing == true) { ?>
            <a id="t_image" href="#" onclick="t_select()"><img src="themes/images/imgb/pic_52.png" /><span class="yes" id="t_yes"></span></a>
        <?php } else { ?>
            <a href="#"><img src="themes/images/imgb/pic_52_02.png" /></a>
        <?php } ?>
    </p>
<!--        <input class="b_btn_07" type="button" value="ok" onclick="NativeApi.close();">-->
    <?php if ($isFacebookBing == false && $isTwitterBing == false) { ?>
        <center><span id="tipContent"><?php echo Yii::t('View', 'Please go to binding'); ?></span></center>
    <?php } ?>
</div>
<input type="hidden" id="isFacebookShare" name="isFacebookShare" value="<?php echo $isFacebookShare; ?>" />
<input type="hidden" id="isTwitterShare" name="isTwitterShare" value="<?php echo $isTwitterShare; ?>" />
<div id="button">

</div>
<script>
    var defaultNum;
    var canDo = true;
    function doShare()
    {
        if(canDo == true)
        {
            var content = $("#shareMessage").attr("value");
            Common.ajaxTimeout=1000000;
            ajaxLoader.get('<?php echo $this->createUrl('Share/ShareImage'); ?>&isTwitterShare='+$("#isTwitterShare").attr("value")+'&isFacebookShare='+$("#isFacebookShare").attr("value")+'&shareMessage='+content+'&shareImageName='+$("#shareImageName").attr("value"),doShareCallback);
        }
        canDo = false;
    }
    function doShareCallback(data)
    {
        if(data.twitterResult==true||data.facebookResult==true)
        {
            CommonDialog.alert('<?php echo Yii::t('View', 'sns success!'); ?>',function(){                                                                        
                NativeApi.close();
            });
        }else
        {
            CommonDialog.alert('<?php echo Yii::t('View', 'qianjia!, share failure! Forget binding an account!'); ?>',function(){                                                                        
                NativeApi.close();
            });   
        }
    }
    function showlcontent(){
        var n = $("#shareMessage").attr('value').length;
        if(n == 0)
        {
            n = defaultNum;
        }
        $("#lcontent").html(n);
    }
    function f_select()
    {
        if($("#f_yes").length>0)
        {
            $("#f_yes").remove();
            $("#isFacebookShare").attr("value","0");
        }else
        {
            $("#f_image").append('<span class="yes" id="f_yes"></span>');
            $("#isFacebookShare").attr("value","1");
        }
        
        checkButtonState();
    }
    function t_select()
    {
        if($("#t_yes").length>0)
        {
            $("#t_yes").remove();
            $("#isTwitterShare").attr("value","0");
            $("#maxNumArea").html('140');
            $("#shareMessage").attr('maxlength','140');
        }else
        {
            $("#t_image").append('<span class="yes" id="t_yes"></span>');
            $("#isTwitterShare").attr("value","1");
            $("#maxNumArea").html('119');
            $("#shareMessage").attr('maxlength','119');
            var str = $("#shareMessage").attr('value');
            str=str.substring(0,119);
            $("#shareMessage").attr('value',str);
            $("#lcontent").html(119);
        }
        
        checkButtonState();
    }
    function checkButtonState()
    {
        if($("#f_yes").length == 0&&$("#t_yes").length == 0)
        {
            $("#button").html('<a class="b_206_gray" style="left: 370px; position: absolute; bottom: 0px;font-size:28px;">OK</a>');
        }else
        {
            $("#button").html('<a class="b_btn_07" style="left: 370px; position: absolute; bottom: 0px;" onclick="doShare()">OK</a>');
        }
    }
    $(document).ready(function(){
        //$("#shareMessage").val() = <?php //echo $shareMessage;          ?>;
        f_select();
        t_select();
        f_select();
        t_select();
        defaultNum = $("#shareMessage").attr('value').length;
        $("#lcontent").html(defaultNum);
    });
</script>