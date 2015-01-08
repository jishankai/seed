<style type="text/css">
    #selectwrapper { 
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

.a_list2 li p em {
    background:none ;padding-left:0px;
}
</style>
<div class="b_con_01 b_bg_01 a_bg_02 a_sl b_bg_03_2">
    <span class="a_bar1"><?php echo Yii::t('Friend', 'friend_01')?><span class="color_white"><?php echo $totalCount.'/'.MAX_FRIEND_NUM?></span></span>
    <div class="b_icobg" onclick="CommonDialog.close('selectFriendContainer')"><a href="#" class="b_ico"></a></div>
    <div class="a_btn06"><?php echo $player->playerName;?><span class="uppercase">id</span>:<span class="color_black"><?php echo $player->inviteId;?></span></div>
    <div class="a_frame06 pt_60">
        <div class="a_btn07 big">
            <i class="color_white"><?php echo Yii::t('Friend', 'friend_13')?></i>
            <input type="text" class="a_btn12" id="searchId" />
            <a href="#" class="a_btn_f a_btn13" onclick="CommonFriend.Search()"><?php echo Yii::t('Friend', 'friend_05')?></a>
        </div>
        <div id="selectwrapper" style="overflow-x: hidden; overflow-y: hidden; left: 0px; ">
            <div id="scroller" style="-webkit-transition-property: -webkit-transform; -webkit-transform-origin-x: 0px; -webkit-transform-origin-y: 0px; -webkit-transform: translate3d(0px, -1px, 0px) scale(1); -webkit-transition-duration: 0ms; ">
                <div id="pullDown" class="">
                </div>
                <ul id="thelist" class="a_list2 w_437" style="overflow: hidden;">
<?php
if (!empty($visualFriend)) {
?>
                    <li style="cursor:pointer;" onclick="selectFriend.over(<?php echo $visualFriend->id?>)">
                    <div class="a_bigpic"><div id="figure<?php echo $visualFriend->id?>"  style="margin:40px 0 0 40px;"></div></div>
    <script>
<?php
    if(empty($visualFriend->favoriteSeed)) {
        echo '$(\'#figure'.$visualFriend->id.'\').parent().html(\'<img src="themes/img/pic_03.png" height="100" width="75"/>\');';
    }else {
        echo 'SeedUnit("figure'.$visualFriend->id.'",'.$favoriteSeed->getFavoriteSeed()->displayData().',0.5);';
    }
?>
    </script>
                    <p><em><?php echo $visualFriend->playerName;?></em><i>lv <?php echo $visualFriend->level;?></i></p>
<?php
    if ($visualFriend->isFoster()) {
        $fosterSeed = $visualFriend->getFosterSeed();
        echo '<a href="#" class="a_btn10"><span id="seed'.$fosterSeed->seedId.'" style="margin-left:20px; float:left;"></span></a>';
        echo '<script>';
        echo '$(function(){SeedUnit("seed'.$fosterSeed->seedId.'",'.$fosterSeed->getDisplayData().',0.5)});';
        echo '</script>';
    }
    else {
        echo '<div style="float:left;width:165px;height:108px;"></div>';
    }
?>
                    </li>
<?php }
?>
<?php  if (!empty($playerFriendList['foster'])) {
    foreach($playerFriendList['foster'] as $friend){ ?>
                    <li style="cursor:pointer;" onclick="selectFriend.over(<?php echo $friend['friend']->playerId;?>)">
                    <div class="a_bigpic"><div id="figure<?php echo $friend['friend']->playerId ?>"  style="margin:40px 0 0 40px;"></div></div>
        <script>
<?php
        if(empty($friend['seed'])) {
            echo '$(\'#figure'.$friend['friend']->playerId.'\').parent().html(\'<img src="themes/img/pic_03.png" height="100" width="75"/>\');';
        }else {
            echo 'SeedUnit("figure'.$friend['friend']->playerId.'",'.$friend['seed']->getDisplayData().',0.5);';
        }
?>
    </script>
                    <p><em><?php echo $friend['friend']->playerName;?></em><i>lv <?php echo $friend['friend']->level;?></i></p>
                    </li>
                    <?php }} ?>

<?php if (!empty($playerFriendList['nofoster'])) {
    foreach($playerFriendList['nofoster'] as $friend){ ?>
                    <li style="cursor:pointer;" onclick="selectFriend.over(<?php echo $friend['friend']->playerId;?>)">
                    <div class="a_bigpic"><div id="figure<?php echo $friend['friend']->playerId ?>"  style="margin:40px 0 0 40px;"></div></div>
        <script>
<?php
        if(empty($friend['seed'])) {
            echo '$(\'#figure'.$friend['friend']->playerId.'\').parent().html(\'<img src="themes/img/pic_03.png" height="100" width="75"/>\');';
        }else {
            echo 'SeedUnit("figure'.$friend['friend']->playerId.'",'.$friend['seed']->getDisplayData().',0.5);';
        }
?>
    </script>
                    <p><em><?php echo $friend['friend']->playerName;?></em><i>lv <?php echo $friend['friend']->level;?></i></p>
<?php
    if (!empty($friend['fosterSeedId'])) {
        $seedId = $friend['fosterSeedId'];
        echo '<a href="#" class="a_btn10"><span id="seed'.$seedId.'" style="margin-left:20px; float:left;"></span></a>';
        echo '<script>';
        echo '$(function(){SeedUnit("seed'.$seedId.'",'.$friend['fosterSeed']->getDisplayData().',0.5)});';
        echo '</script>';
    }
    else {
        echo '<div style="float:left;width:165px;height:108px;"></div>';
    }
?>
                    </li>
                    <?php }} ?>
                </ul>
                <div id="pullUp" class="">
                    <span class="pullUpIcon"></span><span class="pullUpLabel">Pull up to load more...</span>
                </div>
            </div>
        </div>
        <input type="hidden" id="currentPage" value="<?php echo $currentPage?>">
    </div>
</div>
<script type="text/javascript">
window.CommonFriend = {
  moreUrl : '<?php echo $this->createUrl('friend/moreFriend2') ?>',

  Search : function() {
    var searchId = document.getElementById("searchId").value;
    selectFriend.Search(searchId);
        },

  }

  var myScroll,
  pullDownEl, pullDownOffset,
  pullUpEl, pullUpOffset,
  generatedCount = 0;

  function pullUpAction () {
    setTimeout(function () { 
      var currentPage = document.getElementById("currentPage").value;
      AjaxLoader.get(CommonFriend.moreUrl+'&page='+currentPage, function(data){
        var Pmessage=data.Pmessage;
        var page = data.currentPage;
        var figure=data.figure;
        if (Pmessage!='') {
          var el, li, i;
          el = document.getElementById('thelist');
          el.innerHTML = el.innerHTML + Pmessage;
          $("#currentPage").val(page);
          for (var i=0;i<figure.length;i++) {
            if (figure[i]['type']==1) {
              $('#figure'+figure[i]['id']).parent().html('<img src="themes/img/pic_03.png" height="100" width="75"/>');
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

  myScroll = new iScroll('selectwrapper', {
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
}
loaded();
</script>
