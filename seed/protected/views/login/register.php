<style>
#musicFlag {
    position:absolute; left:20px; bottom:20px;
}
</style>
	<div class="b_bg_06">
        <p class="new_logo"></p>
    	<i class="a_ico45 a_ico47" id="musicFlag" style="display:none;"><img src="themes/images/imga/a_ico47.png"><span style="display:none;"></span></i>
        <div class="b_con_11 clearfix">
			<div style="display:table-cell;vertical-align:middle;height:320px; width:558px;">
        	<img src="themes/images/imgb/pic_42.png" alt=""><br>
            <input type="text" name="playerName" class="playerName" value="<?php echo Yii::t('Player', '6 kanji or 12 charactors')?>" maxlength="12" onFocus="if(value==defaultValue){value='';this.style.color='#000'}" onBlur="if(!value){value=defaultValue;this.style.color='#999'}" style="color:#999999" onkeyup="lengthValidate()">
<!--招待ID-->
            <h2 class="f26 mt20"><?php echo Yii::t('Player', 'inviter\'s id')?></h2>
            <p class="f20"><?php echo Yii::t('Player', 'input inviter\'s id and get rewards')?></p>
            <input type="text" name="inviterId" id="inviterId" value="<?php echo Yii::t('Player', 'inviter\'s id can be blank')?>" maxlength="4" onFocus="if(value==defaultValue){value='';this.style.color='#000'}" onBlur="if(!value){value=defaultValue;this.style.color='#999'}" style="color:#999999">
<!---->
            <a href="#_self" class="b_btn_07" id="submit"><?php echo Yii::t('Player', 'register')?></a> 
            </div>
        </div>
    
    </div>	

<SCRIPT type="text/javascript">
var isRegistered = false;
var musicState = 1 ;

$(document).ready(function(){
    jQuery('#submit').bind('click', function(){
        var playerName = $('.playerName').attr('value')==$('.playerName').attr('defaultValue')?'':$('.playerName').val();
        var inviterId = $('#inviterId').attr('value')==$('#inviterId').attr('defaultValue')?'':$('#inviterId').val();

        if (!isRegistered) {
            isRegistered = true;
            ajaxLoader.get("<?php echo $this->createUrl('login/register') ?>&playerName="+encodeURIComponent(playerName)+"&inviterId="+encodeURIComponent(inviterId),function(data){
                NativeApi.callback({
                    'action':'registersuccess',
                    'playerId':data.playerId
                });
            });
            setTimeout("isRegistered = false", 2000);
        }
        
    });

    $('#musicFlag').click(function(){
        musicState = !musicState?1:0;
        if( musicState ) {
            $('#musicFlag').removeClass('op05');
            $('#musicFlag>span').hide();
        }
        else {
            $('#musicFlag').addClass('op05');
            $('#musicFlag>span').show();
        }
        //NativeApi.callback('musicoff',(musicState?0:1));
    });
});
/**
* 字符串求长度(全角) 
*/
String.prototype._getLength = function() {
    var str = this;
    var len = str.length;
    var reLen = 0;
    for (var i = 0; i < len; i++) {        
        if (str.charCodeAt(i) < 27 || str.charCodeAt(i) > 126) {
            // 全角    
            reLen += 2;
        } else {
            reLen++;
        }
    }
    return reLen;    
}
/**
* 字符串截取部分(全角)
* @param {Object} len
*/
String.prototype._cutString = function(len) {
    var str = this;
    var l = str.length;
    var rel = [];
    var tl = 0;
    for (var i = 0; i < l && tl < len; i++) {
        rel[i] = str[i];
        if (str.charCodeAt(i) < 27 || str.charCodeAt(i) > 126) {            
            tl += 2;
        } else {
            tl++;
        }
    }
    return rel.join("");
}

var DEFAULT_LENGTH = 12;

function lengthValidate() {
    var len = DEFAULT_LENGTH - $(".playerName").val()._getLength();
    if (len < 0) {
        $(".playerName").val($(".playerName").val()._cutString(DEFAULT_LENGTH));
        $(".playerName").attr("maxLength", $(".playerName").val().length);
    } else {
        $(".playerName").attr("maxLength", DEFAULT_LENGTH);
    }
}

</SCRIPT>
