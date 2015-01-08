<!--list_05 start-->
<div <?php $background = ModuleUtil::loadconfig('admin', 'background'); if($background['SHOW_BACKGROUND_COLOR'] == 1){ ?> style="background-color:<?php echo $background['BACKGROUND_COLOR'] ?>" <?php } ?>>
<a href="<?php echo $this->createUrl('add')?>">create notice</a>
<ul class="list_05 bg_border_yellow a_01 mt40">
<?php
    $noticeList = $notice->getAllEditNotice(); $i=0;
    foreach($noticeList as $notice){
		$i++;
?>
    <li class="pr">
            <a href="<?php echo $this->createUrl('edit',array('noticeId' => $notice['noticeId']))?>">
                <div class="p10 bold f14 ls-2" style="word-wrap:nomal;word-break:break-all;"><?php echo $notice['title'] ?></div>
            </a>
    </li>
<!--list_05 end-->
<?php 
    }
?> 
</ul>
</div>