<style type="text/css">
    #wrapper { 
        position:relative; 
        z-index:3; 
        top:0px; 
        bottom:48px;
        width:871px; 
        height:352px;  
        overflow:hidden; 
    }
    #scroller { 
        position:absolute; 
        z-index:3; /*	-webkit-touch-callout:none;*/ 
        -webkit-tap-highlight-color:rgba(0,0,0,0); 
        width:100%; 
        padding:0; 
    }
    /**
    *
    * Pull down styles
    *
    */
    #pullDown {
        text-align: center;
        border-bottom:1px solid #ccc;
    }
    #pullUp {
        text-align: center;
        background:#fff;
        height:40px;
        line-height:40px;
        padding:5px 10px;
        border-bottom:1px solid #ccc;
        font-weight:bold;
        font-size:14px;
        color:#888;
    }
</style>
<div class="b_con_01 b_bg_01 a_bg_02">
  <span class="a_bar1"><?php echo Yii::t('Friend', 'friend_01')?><span class="color_white"><span class="totalCount"><?php echo $totalCount?></span><?php echo '/'.MAX_FRIEND_NUM?></span></span>
  <i class="a_ico1 a_ico1_f1 on" onclick="CommonFriend.goFriendUrl(0)"><img src="themes/images/imga/a_ico38_2.png" /></i>
  <i class="a_ico1 a_ico1_f2" onclick="CommonFriend.goFriendUrl(1)"><img src="themes/images/imga/a_ico36_2.png" /><i class="a_ico6_2"></i></i>
  <i class="a_ico1 a_ico1_f2" onclick="CommonFriend.goFriendUrl(2)"><img src="themes/images/imga/a_ico36_2.png" /><i class="a_ico2_2"></i></i>
  <a href="#" class="a_btn04" onclick="NativeApi.close()"></a>
  <div class="a_btn06"><?php echo $player->playerName;?><span class="uppercase">id</span>: <span class="color_black"><?php echo $player->inviteId;?></span></div>
  <div class="a_frame06 pt_60">
    <div class="a_btn07">
      <div class="a_btn08"><span class="name"><?php echo Yii::t('Friend', 'friend_02')?></span><span class="num"><span class="fosterNum"><?php echo $fosterNum?></span><?php echo '/'.$fosterMax?></span></div>
<?php
if ($fosterNum>0) {
?>
      <a href="#" class="a_btn_f a_btn09" id="recycleSeeds" onclick="CommonFriend.RecAll()"><?php echo Yii::t('Friend', 'friend_03')?></a>
<?php
} else {
?>
      <a href="#"></a>
<?php
}
?>
    </div>
    <div id="wrapper">
      <div id="scroller">
        <div id="pullDown" class="">
        </div>
        <ul id="thelist" class="a_list2 w_871" style="overflow: hidden;">
<?php
if (!empty($visualFriend)) {
?>
          <li id="friend<?php echo $visualFriend->id?>">
          <div class="a_bigpic"><div id="figure<?php echo $visualFriend->id?>" style="margin:40px 0 0 40px;"></div></div>
    <script>
<?php
    if(empty($visualFriend->favoriteSeed)) {
        echo '$("#figure'.$visualFriend->id.'").parent().html(\'<img src="themes/img/pic_03.png" height="100" width="75">\');';
    }else {
        echo 'SeedUnit("figure'.$visualFriend->id.'",'.$visualFriend->getFavoriteSeed()->getDisplayData().',0.5);';
    }
?>
    </script>
          <p><em id="Name<?php echo $visualFriend->id?>"><?php echo $visualFriend->playerName;?></em><i>lv <?php echo $visualFriend->level;?></i></p>
          <span class='fosterSeed'>
<?php
    if ($visualFriend->isFoster()) {
        $fosterSeed = $visualFriend->getFosterSeed();
        echo '<a href="#" class="a_btn10" onclick="CommonFriend.Rec('.$visualFriend->id.','.$fosterSeed->seedId.')"><span id="seed'.$fosterSeed->seedId.'" style="margin-left:20px; margin-top:10px; float:left;"></span></a>';
        echo '<script>';
        echo 'SeedUnit("seed'.$fosterSeed->seedId.'",'.$fosterSeed->getDisplayData().',0.5);';
        echo '</script>';
    }
    else {
        echo '<div style="float:left;width:165px;height:108px;"></div>';
    }
?>
          </span>
<div class="friend_btn">
          <a href="#" class="a_btn11 a_ico42" onclick="NativeApi.callback({action:'visualVisit',playerId:'<?php echo $visualFriend->id ?>',gardenId:'<?php echo $visualFriend->defaultGarden?>'});"><span></span></a>
</div>
          </li>
<?php
}
?>
<?php 
if (!empty($playerFriendList['foster'])) {
    foreach($playerFriendList['foster'] as $friend){ ?>
          <li id="friend<?php echo $friend['friend']->playerId ?>">
          <div class="a_bigpic"><div id="figure<?php echo $friend['friend']->playerId ?>" style="margin:40px 0 0 40px;"></div></div>
        <script>
<?php
        if(empty($friend['seed'])) {
            echo '$("#figure'.$friend['friend']->playerId.'").parent().html(\'<img src="themes/img/pic_03.png" height="100" width="75">\');';
        }else {
            echo 'SeedUnit("figure'.$friend['friend']->playerId.'",'.$friend['seed']->getDisplayData().',0.5);';
        }
?>
    </script>
          <p><em id="Name<?php echo $friend['friend']->playerId ?>"><?php echo $friend['friend']->playerName;?></em><i>lv <?php echo $friend['friend']->level;?></i></p>
          <span class='fosterSeed'>
<?php
    if (!empty($friend['fosterSeedId'])) {
        $seedId = $friend['fosterSeedId'];
        echo '<a href="#" class="a_btn10" onclick="CommonFriend.Rec('.$friend['friend']->playerId.','.$seedId.')"><span id="seed'.$seedId.'" style="margin-left:30px; margin-top:25px; float:left;"></span></a>';
        echo '<script>';
        echo 'SeedUnit("seed'.$seedId.'",'.$friend['fosterSeed']->getDisplayData().',0.45);';
        echo '</script>';
    }
    else {
        echo '<div style="float:left;width:165px;height:108px;"></div>';
    }
?>
          </span>
<div class="friend_btn">
          <a href="#" class="a_btn11 a_ico42" onclick="NativeApi.callback({action:'visit',playerId:'<?php echo $friend['friend']->playerId ?>'});"><span></span></a>
          <a href="#" class="a_btn11 a_ico47" onclick="CommonFriend.Del(<?php echo $friend['friend']->playerId?>)"><span></span></a>
</div>
          </li>
<?php }
} ?>

<?php 
if (!empty($playerFriendList['nofoster'])) {
    foreach($playerFriendList['nofoster'] as $friend){ ?>
          <li id="friend<?php echo $friend['friend']->playerId ?>">
          <div class="a_bigpic"><div id="figure<?php echo $friend['friend']->playerId ?>" style="margin:40px 0 0 40px;"></div></div>
        <script>
<?php
        if(empty($friend['seed'])) {
            echo '$("#figure'.$friend['friend']->playerId.'").parent().html(\'<img src="themes/img/pic_03.png" height="100" width="75">\');';
        }else {
            echo 'SeedUnit("figure'.$friend['friend']->playerId.'",'.$friend['seed']->getDisplayData().',0.5);';
        }
?>
    </script>
          <p><em id="Name<?php echo $friend['friend']->playerId ?>"><?php echo $friend['friend']->playerName;?></em><i>lv <?php echo $friend['friend']->level;?></i></p>
          <span class='fosterSeed'>
<?php
    if (!empty($friend['fosterSeedId'])) {
        $seedId = $friend['fosterSeedId'];
        echo '<a href="#" class="a_btn10" onclick="CommonFriend.Rec('.$friend['friend']->playerId.','.$seedId.')"><span id="seed'.$seedId.'" style="margin-left:30px; margin-top:25px; float:left;"></span></a>';
        echo '<script>';
        echo 'SeedUnit("seed'.$seedId.'",'.$friend['fosterSeed']->getDisplayData().',0.45);';
        echo '</script>';
    }
    else {
        echo '<div style="float:left;width:165px;height:108px;"></div>';
    }
?>
          </span>
<div class="friend_btn">
          <a href="#" class="a_btn11 a_ico42" onclick="NativeApi.callback({action:'visit',playerId:'<?php echo $friend['friend']->playerId ?>'});"><span></span></a>
          <a href="#" class="a_btn11 a_ico47" onclick="CommonFriend.Del(<?php echo $friend['friend']->playerId?>)"><span></span></a>
</div>
          </li>
<?php }
} ?>
        </ul>
        <div id="pullUp" class="">
          <span class="pullUpIcon"></span><span class="pullUpLabel">Pull up to load more...</span>
        </div>
      </div>
    </div>
    <input type="hidden" id="currentPage" value="<?php echo $currentPage?>">
    <input type="hidden" id="deleteNum" value="0">
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
    nextStep : function(){
        this.step ++ ;
        switch( this.step ) {
        	case 1 :
        		$('#scroller').hide();
        		this.showMessage1();
        		break ;
            case 2 :
                this.showMessage2();
                break ;
            default: 
                this.over();
                console.log(this.step);
        }
    } ,
    showMessage1 : function() {
		var self = this ;
		$('#guideDialogMessage').html('<?php echo Yii::t('GuideMessage', 'message_101')?>').fadeIn().css({top:0, right:0});;
		$('#page').append('<em class="star" style="position:absolute; top:14%; left:11%; z-index:10"></em><em class="star" style="position:absolute; top:14%; left:21%"; z-index:10></em><em class="star" style="position:absolute; top:14%; left:31%"; z-index:10></em><em class="star" style="position:absolute; top:14%; left:41%"; z-index:10></em>');
		$('.long').click(function(){FriendGuide.nextStep()});
    },
    showMessage2 : function() {
        var self = this ;
        $('.star').remove();
        $('.long').unbind('click');
        $('#guideDialogMessage').html('<?php echo Yii::t('GuideMessage', 'message_102')?>').fadeIn().parent().children('.b_text05goon').remove();
		$('#page').append('<em class="new_hand" style="position:absolute; top:8%; left:71%; z-index:10;"><span class="new_hand1"></span><span class="new_hand2"></span></em><em class="star" id="toRandom" style="position:absolute; top:4%; left:69%; z-index:9001;"></em><em class="fangxiang new_left" style="position:absolute; top:5%; left:80%; z-index:10;"></em>');
        $('#toRandom').click(function(){
        	CommonFriend.goFriendUrl(1);
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
window.CommonFriend = {
  delUrl : '<?php echo $this->createUrl('friend/deleteFriend'); ?>',
  recUrl : '<?php echo $this->createUrl('friend/recycleSeed')?>',
  moreUrl : '<?php echo $this->createUrl('friend/moreFriend') ?>',
  AjaxUrl : '<?php echo $this->createUrl('friend/AjaxShow') ?>',
  callBack : '',
  Lock : true,

  goFriendUrl : function(id) {
	  AjaxLoader.get(CommonFriend.AjaxUrl+'&category='+id,CommonFriend.AjaxCB);
	  },

  AjaxCB : function (data) {
	  $("#page").html(data);
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
         var friendId=data.friendId;
         //$("#friend"+friendId).hide();
         document.getElementById("deleteNum").value++;
         //更新好友数量
         $(".totalCount").html(parseInt($(".totalCount").html())-1);
         var name = $("#Name"+friendId).html();
     	 var message2 = "<?php echo Yii::t('Friend', 'delete friend')?>";
     	 message2 = message2.replace('{name}',name);
         CommonDialog.alert(message2);
       },

  Rec : function(id1,id2) {
      CommonDialog.confirm ("<?php echo Yii::t('Friend', 'friend_07')?>",function(){
        AjaxLoader.get(CommonFriend.recUrl+'&friendId='+id1+'&seedId='+id2,CommonFriend.Recycle);
                });
    },

  Recycle : function(data) {
      var seedId = data.seedId;
      $("#seed"+seedId).parent().parent().html('<div style="float:left;width:165px;height:108px;"></div>');
      $("span.fosterNum").html(parseInt($("span.fosterNum").html())-1);
      if ($("span.fosterNum").html()==0) {
        $("#recycleSeeds").hide();  
            }
        },

  RecAll : function() {
          var url='<?php echo $this->createUrl('friend/recycleSeeds') ?>';
          CommonDialog.confirm ("<?php echo Yii::t('Friend', 'friend_08')?>",function(){
            AjaxLoader.get(url,CommonFriend.RecycleAll);
                   });
      },

  RecycleAll : function(data) {
         var seedIds = data.seedIds;
         for (var i=0;i<seedIds.length;i++) {
           $("#seed"+seedIds[i]).parent().parent().html('<div style="float:left;width:165px;height:108px;"></div>');
         }
         $("#recycleSeeds").hide();
         $("span.fosterNum").html(0);
         var message = "<?php echo Yii::t('Friend', 'friend_09')?>";
       	 CommonDialog.alert(message);
      },
}

var myScroll,
pullDownEl, pullDownOffset,
pullUpEl, pullUpOffset,
generatedCount = 0;

function pullUpAction () {
  setTimeout(function () { 
    var currentPage = document.getElementById("currentPage").value;
    var num = document.getElementById("deleteNum").value;
    AjaxLoader.get(CommonFriend.moreUrl+'&page='+currentPage+'&delnum='+num,function(data){
      var Pmessage=data.Pmessage;
      var page = data.currentPage;
      var figure=data.figure;
      if (Pmessage!='') {
        var el, li, i;
        el = document.getElementById('thelist');
        $(el).append(Pmessage);
        $("#currentPage").val(page);
        for (var i=0;i<figure.length;i++) {
          if (figure[i]['type']==1) {
            $('#figure'+figure[i]['id']).parent().html('<img src="themes/img/pic_03.png" />');
                                                  }else {
                                                    eval( "var displayData="+ figure[i]['data'] );
                                                    SeedUnit("figure"+figure[i]['id'],displayData,0.5);
                                                  }
                                              }
                                          } else {
                                            pullUpEl.className = '';
                                            pullUpEl.querySelector('.pullUpLabel').innerHTML = 'No More';
                                            $('#pullUp').hide();
                                            this.maxScrollY = pullUpOffset;

                                          }
                                          myScroll.refresh();
});
// Remember to refresh when contents are loaded (ie: on ajax completion)
}, 0);          	// <-- Simulate network congestion, remove setTimeout from production!
}

function loaded() {
    if (<?php echo count($playerFriendList)==$totalCount?1:0?>) {
        $('#pullUp').hide();
    }
  pullDownEl = document.getElementById('pullDown');
  pullDownOffset = pullDownEl.offsetHeight;
  pullUpEl = document.getElementById('pullUp');	
  pullUpOffset = pullUpEl.offsetHeight;

  myScroll = new iScroll('wrapper', {
    vScrollbar:false,
    useTransition: true,
    topOffset: pullDownOffset,
    onRefresh: function () {
      if (pullUpEl.className.match('loading')) {
        pullUpEl.className = '';
        pullUpEl.querySelector('.pullUpLabel').innerHTML = 'More...';
    }
  },
  onScrollMove: function () {
    if (this.y > 5 && !pullDownEl.className.match('flip')) {
      pullDownEl.className = 'flip';
      this.minScrollY = 0;
          } else if (this.y < 5 && pullDownEl.className.match('flip')) {
            pullDownEl.className = '';
            this.minScrollY = -pullDownOffset;
          } else if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip')) {
            pullUpEl.className = 'flip';
            pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Release to refresh...';
            this.maxScrollY = this.maxScrollY;
          } else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
            pullUpEl.className = '';
            pullUpEl.querySelector('.pullUpLabel').innerHTML = 'More...';
            this.maxScrollY = pullUpOffset;
          }
      },
    onScrollEnd: function () {
        if (pullDownEl.className.match('flip')) {
          pullDownEl.className = 'loading';
    } else if (pullUpEl.className.match('flip')) {
      pullUpEl.className = 'loading';
      pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Loading...';				
      setTimeout(pullUpAction(), 0);	// Execute custom function (ajax call?)
              }
          }
});

setTimeout(function () { document.getElementById('wrapper').style.left = '0'; }, 800);
    }

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

<?php if ($this->actionType == REQUEST_TYPE_AJAX) { ?>
loaded();
<?php } else { ?>
LoadAction.push(function(){loaded();});
<?php } ?>


</script>
