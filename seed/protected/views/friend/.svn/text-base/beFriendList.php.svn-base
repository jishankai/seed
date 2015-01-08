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
    <i class="a_ico1 a_ico1_f1" onclick="CommonFriend.goFriendUrl(0)"><img src="themes/images/imga/a_ico38_2.png" /></i>
    <i class="a_ico1 a_ico1_f2" onclick="CommonFriend.goFriendUrl(1)"><img src="themes/images/imga/a_ico36_2.png" /><i class="a_ico6_2"></i></i>
    <i class="a_ico1 a_ico1_f2 on" onclick="CommonFriend.goFriendUrl(2)"><img src="themes/images/imga/a_ico36_2.png" /><i class="a_ico2_2"></i></i>
    <a href="#" class="a_btn04" onclick="NativeApi.close()"></a>
    <div class="a_btn06"><?php echo $player->playerName;?><span class="uppercase">id</span>: <span class="color_black"><?php echo $player->inviteId;?></span></div>
    <div class="a_frame06 fqq">
    <?php if ($num == 0) {?>
    <div class="a_frame08" id="empty">
                	<img src="themes/images/imgb/img_02.png" alt="">
                	<div class="quan1"><?php echo Yii::t('Friend', 'friend_12')?></div>
            	</div>
    <?php }?>
        <div id="wrapper">
            <div id="scroller">
                <div id="pullDown" class="">
                </div>
                <ul id="thelist" class="a_list2 w_437 " id="page1" style="overflow: hidden;">
                    <?php foreach($friendRequestList as $friend){ ?>
                    <li id="friend<?php echo $friend['friend']->playerId?>">
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
                    <p><em id="Name<?php echo $friend['friend']->playerId?>"><?php echo $friend['friend']->playerName;?></em><i>lv <?php echo $friend['friend']->level;?></i></p>
                    <a id="confirm<?php echo $friend['friend']->playerId?>" href="#" class="a_btn11 ml5 a_ico44"onclick="CommonFriend.Confirm(<?php echo $friend['friend']->playerId?>)"><span></span></a>
                    <a id="refuse<?php echo $friend['friend']->playerId?>" href="#" class="a_btn11 a_ico47"onclick="CommonFriend.Refuse(<?php echo $friend['friend']->playerId?>)"><span></span></a>
                    </li>
                    <?php } ?>
                </ul>
                <div id="pullUp" class="">
                    <span class="pullUpIcon"></span><span class="pullUpLabel">Pull up to load more...</span>
                </div>
            </div>
        </div>
        <div>
        	<input type="hidden" id="num" value="<?php echo $num?>">
            <input type="hidden" id="currentPage" value="<?php echo $currentPage?>">
            <input type="hidden" id="deleteNum" value="0">
        </div>
    </div>
</div>

<script type="text/javascript"> 
    window.CommonFriend = {
        ConfirmUrl : '<?php echo $this->createUrl('friend/confirmFriendRequest') ?>',
        RefuseUrl : '<?php echo $this->createUrl('friend/refuseFriendRequest') ?>',
        moreUrl : '<?php echo $this->createUrl('friend/moreFriend3') ?>',
        AjaxUrl : '<?php echo $this->createUrl('friend/AjaxShow') ?>',
        callBack : '',
        Lock : true,

        goFriendUrl : function(id) {
      	  AjaxLoader.get(CommonFriend.AjaxUrl+'&category='+id,CommonFriend.AjaxCB);
      	  },

        AjaxCB : function (data) {
      	  $("#page").html(data);
      	  },

        Confirm : function(id) {
      		if (CommonFriend.Lock==true) {
        		CommonFriend.Lock=false;
        		$("#confirm"+id).hide();
                $("#refuse"+id).hide();
            	AjaxLoader.get(CommonFriend.ConfirmUrl+'&friendId='+id,CommonFriend.ConfirmCB);
            	setTimeout(function(){
	  				CommonFriend.Lock = true;
	    			}, 2000);
      		}
        },

        ConfirmCB : function(data) {
                        var friendId=data.friendId;
                        //$("#confirm"+friendId).hide();
                        //$("#refuse"+friendId).hide();
                        document.getElementById("deleteNum").value++;
                        //更新好友数量
                        var currentCount = parseInt($(".totalCount").html())+1;
                        var maxCount = <?php echo MAX_FRIEND_NUM?>;
                        $(".totalCount").html(Math.min(currentCount, maxCount));
                        var name = $("#Name"+friendId).html();
                        var message = "<?php echo Yii::t('Friend', 'be friend')?>";
                        message = message.replace('{name}',name);
                        CommonDialog.alert(message);
                    },

        Refuse : function(id) {
                    	if (CommonFriend.Lock==true) {
                    		CommonFriend.Lock=false;
                   	 		var name = $("#Name"+id).html();
                     		var message = "<?php echo Yii::t('Friend', 'friend_10')?>";
                     		message = message.replace('{name}',name);
                     		CommonDialog.confirm (message,function(){
                     			$("#confirm"+id).hide();
                                $("#refuse"+id).hide();
                         		AjaxLoader.get(CommonFriend.RefuseUrl+'&friendId='+id,CommonFriend.RefuseCB);
                     			});
                     		setTimeout(function(){
            	  				CommonFriend.Lock = true;
            	    			}, 2000);
                    	}
                 },

        RefuseCB : function(data) {
                       var friendId=data.friendId;
                       //$("#confirm"+friendId).hide();
                       //$("#refuse"+friendId).hide();
                       document.getElementById("deleteNum").value++;
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
                //el.innerHTML = el.innerHTML + Pmessage;
                $("#currentPage").val(page);
                for (var i=0;i<figure.length;i++) {
                    if (figure[i]['type']==1) {
                        $("#figure"+figure[i]['id']).parent().html('<img src="themes/img/pic_03.png" height="100" width="75">');
                    }else {
                    	eval( "var displayData="+ figure[i]['data'] );
                        SeedUnit("figure"+figure[i]['id'],displayData,0.5);
                        //SeedUnit("figure"+figure[i]['id'],figure[i]['data'],0.5);
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
    if (<?php echo count($friendRequestList)<6?1:0?>) {
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
