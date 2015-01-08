朋友总数：<?php echo  $totalCount ?>
<br />
朋友列表
<ul>
<?php foreach($playerFriendList as $friend){ ?>
<li>
<input type="hidden" value="<?php echo $friend['friend']->playerId ?>" name="friendId"/>
<input type="hidden" value="<?php echo $friend['friend']->playerName ?>" name="friendName"/>
<?php echo $friend['friend']->playerName;?>
<a href="#_self" id="deleteFriend<?php echo $friend['friend']->playerId ?>"><?php echo Yii::t('View', 'delete') ?></a>
</li>
<?php } ?>
</ul>
<?php $this->widget('application.components.widget.FriendPage', array('pageNum'=>ceil($totalCount/FRIEND_INDEX_PAGE_NUM), 'currentPage'=>$currentPage, 'id'=>'Bottom')); ?>
<script type="text/javascript">
	$("a[id^='deleteFriend']").click(function() {
		var friendId = $(this).parent().find("input[name='friendId']").attr("value");
		var url='<?php echo $this->createUrl('friend/deleteFriend') ?>&friendId='+friendId;
		ajaxLoader.get(url,Delete)
	}); 
	
	function Delete(data) {
		var friendId=data.friendId;
		$("#deleteFriend"+friendId).parent().hide();
	}
</script>