<div class="b_con_01 b_bg_01 a_bg_02">
	<span class="a_bar1"><?php echo Yii::t('Friend', 'friend_01')?><span class="color_white"><?php echo $totalCount.'/'.MAX_FRIEND_NUM?></span></span>
	<i class="a_ico1 a_ico1_f1" onclick="CommonFriend.goFriendUrl(0)"><img src="themes/images/imga/a_ico38_2.png" /></i>
	<i class="a_ico1 a_ico1_f2 on" onclick="CommonFriend.goFriendUrl(1)"><img src="themes/images/imga/a_ico36_2.png" /><i class="a_ico6_2"></i></i>
	<i class="a_ico1 a_ico1_f2" onclick="CommonFriend.goFriendUrl(2)"><img src="themes/images/imga/a_ico36_2.png" /><i class="a_ico2_2"></i></i>
	<a href="#" class="a_btn04" onclick="NativeApi.close()"></a>
	<div class="a_btn06"><?php echo $player->playerName;?><span class="uppercase">id</span>: <span class="color_black"><?php echo $player->inviteId;?></span></div>
	<div class="a_frame06 pt_60<?php if ($type==2) echo ' last'; ?> ">
		<div class="a_btn07 big">
			<i class="color_white"><?php echo Yii::t('Friend', 'friend_04')?></i>
			<input type="text" class="a_btn12" id="searchId" value="<?php echo $inviteId?>"/>
			<a href="#" class="a_btn_f a_btn13" onclick="CommonFriend.Search()"><?php echo Yii::t('Friend', 'friend_05')?></a>
			<a href="#" class="a_btn_f a_btn14" id="ran" onclick="CommonFriend.goFriendUrl(3)" style="display:none"><?php echo Yii::t('Friend', 'friend_06')?></a>
			<a href="#" class="a_btn_f a_btn14" id="time">00:00:00</a>
		</div>
		<?php if ($type==1) {?>
		<ul class="a_list2 w_437" style="overflow: hidden;">
		<?php if (count($playerFriendList)==0) {?>
			<div class="a_frame08" id="empty">
                <img src="themes/images/imgb/img_02.png" alt="">
                <div class="quan1"><?php echo Yii::t('Friend', 'friend_12')?></div>
            </div>
		<?php }?>
		<div id="scrollElement">
		<?php foreach($playerFriendList as $friend){ ?>
		<li>
			<div class="a_bigpic"> <div id="figure<?php echo $friend['playerId']; ?>" style="margin:40px 0 0 40px;"></div></div>
			<script>
				<?php
				if(empty($friend['seed'])) {
					echo '$(\'#figure'.$friend['playerId'].'\').parent().html(\'<img src="themes/img/pic_03.png" height="100" width="75"/>\');';
				}else {
					echo 'SeedUnit("figure'.$friend['playerId'].'",'.$friend['seed']->getDisplayData().',0.5);';
				}
				?>
			</script>
			<p><em><?php echo $friend['player']->playerName;?></em><i>lv <?php echo $friend['player']->level;?></i></p>
			<a href="#" class="a_btn11 a_ico42" onclick="NativeApi.callback({action:'visit',playerId:'<?php echo $friend['playerId']; ?>'});"><span></span></a>
<?php
if ($friend['status']!=1 && $friend['status']!=0) {
?>
			<a href="#" class="a_btn11 a_ico44" onclick="CommonFriend.Create(<?php echo $friend['playerId'];?>)"><span class="createRequest" id="<?php echo $friend['playerId']?>"></span></a>
<?php
} else {
?>
            <a href="#"><span></span></a>
<?php
}
?>
		</li>
		<?php }?>
		</div>
		</ul>
		<?php }else{?>
		<ul class="a_list2  w_871">
		<?php if (count($playerFriendList)==0) {?>
			<div class="a_frame08" id="empty">
                <img src="themes/images/imgb/img_02.png" alt="">
                <div class="quan1"><?php echo Yii::t('Friend', 'friend_11')?></div>
            </div>
		<?php }?>
		<?php foreach($playerFriendList as $friend){ ?>
			<li id="friend<?php echo $friend['player']->playerId ?>">
				<div class="a_bigpic"><div id="figure<?php echo $friend['playerId']; ?>" style="margin:5px 0 0 15px;"></div></div>
				<script>
				<?php
				if(empty($friend['seed'])) {
					echo '$(\'#figure'.$friend['playerId'].'\').parent().html(\'<img src="themes/img/pic_03.png" height="100" width="75"/>\');';
				}else {
					echo 'SeedUnit("figure'.$friend['playerId'].'",'.$friend['seed']->getDisplayData().');';
				}
				?>
			</script>
				<p><em id="Name<?php echo $friend['player']->playerId ?>"><?php echo $friend['player']->playerName;?></em><i>lv <?php echo $friend['player']->level;?></i></p>
				<?php 
					switch ($friend['status']){
						case 0:
							echo '<a href="#" class="a_btn11 a_ico42" onclick="NativeApi.callback({action:\'visit\',playerId:\''.$friend['playerId'].'\'});"><span></span></a>';
							echo '<a href="#" class="a_btn11 a_ico47" onclick="CommonFriend.Del('.$friend['player']->playerId.')"><img src="themes/images/imgb/pic_47.png" /></a>';
							break;
						case 1:
							echo '<a href="#" class="a_btn11 a_ico44 op04"><span></span></a>';
							break;
						default:
							echo '<a href="#" class="a_btn11 a_ico44" onclick="CommonFriend.Create('.$friend['playerId'].')"><span class="createRequest" id="search'.$friend['playerId'].'"></span></a>';
							break;
					}
				?>
			</li>
        <?php }?>
		</ul>
		<?php }?>
	</div>
</div>

<?php if ($isGuide == 1) {?>

<div style="width:421px; height:337px; overflow:hidden; position:absolute; bottom:0px; right:0px; z-index:9001;">
    <div class="b_text06 b_text06_1" style="float:left">
        <i class="text06pic"></i>
        <div class="long">
            <span id="guideDialogMessage">
            </span>
            <em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
        </div>
    </div>
</div>

<script language="javascript">
var FriendGuide = {
    step : 0 ,
    zIndex : 9001 ,
    visualId : 0 ,
    gardenId : 0 ,
    getUrl : '<?php echo $this->createUrl('friend/getVisualFriend') ?>',
    nextStep : function(){
        this.step ++ ;
        switch( this.step ) {
        	case 1 :
        		this.showMessage1();
        		break ;
            case 2 :
                this.showMessage2();
                break ;
            case 3 :
            	this.showMessage3();
            	break ;
            default: 
                this.over();
                console.log(this.step);
        }
    } ,
    showMessage1 : function(){
        var self = this ;
        $('#guideDialogMessage').html('<?php echo Yii::t('GuideMessage', 'message_103')?><br />').fadeIn();
        $('#page').append('<em class="star" style="position:absolute; top:24%; left:70%; z-index:10;"></em><em class="star" style="position:absolute; top:24%; left:79%; z-index:10;"></em>');
        $('.long').click(function(){FriendGuide.nextStep()});
		$('#empty').hide();
        AjaxLoader.get(FriendGuide.getUrl,function(data){
        	var Pmessage=data.Pmessage;
        	var figure=data.figure;
        	if (Pmessage!='') {
        		var el, li, i;
        		el = document.getElementById('scrollElement');
        		$('#scrollElement').html(Pmessage);
        		if (figure['type']==1) {
        			$('#figure'+figure['id']).parent().html('<img src="themes/img/pic_03.png" />');
            	}else {
            		eval( "var displayData="+ figure['data'] );
            		SeedUnit("figure"+figure['id'],displayData,0.5);
                }
        		FriendGuide.visualId=figure['id'];
        		FriendGuide.gardenId=figure['id2'];
            }
        });
    } ,
    
    showMessage2 : function() {
    	var self = this ;
        $('.star').remove();
        $('.long').unbind('click');
    	$('#guideDialogMessage').html('<?php echo Yii::t('GuideMessage', 'message_104')?>').fadeIn().parent().children('.b_text05goon').remove();
    	$('#page').append('<em class="new_hand" style="position:absolute; top:55%; left:41%; z-index:10;"><span class="new_hand1"></span><span class="new_hand2"></span></em><em class="star" id="addFriend" style="position:absolute; top:50%; left:38%; z-index:9001;"></em><em class="fangxiang new_left" style="position:absolute; top:52%; left:48%; z-index:9002;"></em>');
        $('#addFriend').click(function(){
			$('#createVisualFriend').hide();
			FriendGuide.nextStep();
        });
    } ,
    showMessage3 : function() {
		var self = this ;
		$('.new_hand').remove();
		$('.star').remove();
		$('.fangxiang').remove();
		$('#guideDialogMessage').html('<?php echo Yii::t('GuideMessage', 'message_105')?>').fadeIn();
		$('#page').append('<em class="new_hand" style="position:absolute; top:55%; left:30%; z-index:10;"><span class="new_hand1"></span><span class="new_hand2"></span></em><em class="star" id="visitFriend" style="position:absolute; top:50%; left:28%; z-index:9001;"></em><em class="fangxiang new_left" style="position:absolute; top:52%; left:38%; z-index:9002;"></em>');
		$('#visitFriend').click(function(){
			NativeApi.callback({action:'visualVisit',playerId:(FriendGuide.visualId),gardenId:(FriendGuide.gardenId)});
        });
    }
}
$(document).ready(function(){
	FriendGuide.nextStep();
	$('div.guide_main_cover').show().css('z-index',9000);
}) ;
</script>

<?php }?>

<script type="text/javascript">
$(document).ready(function() {
	if (<?php echo $cdTime?>!=0){
		SeedTimer.start("time",<?php echo $cdTime?>,Refresh);
	}else {
		Refresh();
	}
});


<?php if ($this->actionType == REQUEST_TYPE_AJAX) { ?>
Flipsnap('#scrollElement',{
    position:'y',
    distance:160,
    maxPoint:1 
});
<?php } else { ?>
LoadAction.push(function(){
    Flipsnap('#scrollElement',{
        position:'y',
        distance:50,
        maxPoint:1 
    });
});
<?php } ?>

function Refresh() {
	$("#time").hide();
	$("#ran").show();
}
    
window.CommonFriend = {
	createUrl : '<?php echo $this->createUrl('friend/createFriendRequest') ?>',
	delUrl : '<?php echo $this->createUrl('friend/deleteFriend'); ?>',
	AjaxUrl : '<?php echo $this->createUrl('friend/AjaxShow') ?>',
	callBack : '',
	Lock : true,

	goFriendUrl : function(id) {
		  AjaxLoader.get(CommonFriend.AjaxUrl+'&category='+id,CommonFriend.AjaxCB);
		  },

	AjaxCB : function (data) {
		  $("#page").html(data);
		  },
	
	Search : function() {
		var ID = document.getElementById("searchId").value;
		var datePattern = new RegExp("^[a-zA-Z0-9]{4}$");
		if (datePattern.test(ID)) {
			AjaxLoader.get(CommonFriend.AjaxUrl+'&category=1&inviteId='+ID,CommonFriend.AjaxCB);
		}else {
			CommonDialog.alert("<?php echo Yii::t('Friend', 'id error')?>")
		}
	},

	Del : function(id) {
		if (CommonFriend.Lock==true) {
			CommonFriend.Lock=false;
			var name = $("#Name"+id).html();
			var message = "<?php echo Yii::t('Friend', 'del friend')?>";
			message = message.replace('{name}',name);
			CommonDialog.confirm (message,function(){
				$("#friend"+id).hide();
				AjaxLoader.get(CommonFriend.delUrl+'&friendId='+id,CommonFriend.Delete);
			});
			setTimeout(function(){
  				CommonFriend.Lock = true;
    			}, 2000);
		}
	},
	
	Delete : function(data) {
		//var friendId=data.friendId;
		//$("#friend"+friendId).hide();
		
	},
	
	Create : function(id) {
		if (CommonFriend.Lock==true) {
    		CommonFriend.Lock=false;
    		$(".createRequest#"+id).parent().hide();
			ajaxLoader.get(CommonFriend.createUrl+'&friendId='+id,CommonFriend.CreateCB);
			setTimeout(function(){
  				CommonFriend.Lock = true;
    			}, 2000);
  		}
	},
	
	CreateCB : function(data) {
		//隐藏添加好友按钮
        //$(".createRequest#"+data.friendId).parent().hide();
        //精确搜索添加好友后隐藏好友记录并重置搜索栏
        $(".createRequest#search"+data.friendId).parent().parent().hide();
        $("#searchId").val('');
        CommonDialog.alert("<?php echo Yii::t('Friend', 'apply success')?>");
	}
} 
</script>
