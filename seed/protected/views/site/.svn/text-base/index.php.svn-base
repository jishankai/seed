<div style="width:50%; float:left;">
<h1>功能Demo</h1>

<p>
<ul>
	<?php foreach($methodData as $name=>$comment ) {?>
	<li>index.php?r=demo/<?php echo $name; ?>  
	<a href="<?php echo $this->createUrl('demo/'.$name);?>" target="_blank">访问</a>
	<tt><?php echo nl2br($comment); ?></tt> 
	</li>
	<?php } ?> 
</ul>

</p>
</div>
<h1>Ajax测试</h1>
<p>
<a href="javascript:ajaxLoader.get('<?php echo $this->createUrl('demo/ajax'); ?>')">通用Ajax请求</a> 
<br /><br />
<a href="javascript:ajaxLoader.get('<?php echo $this->createUrl('demo/ajax'); ?>',function(data){ alert(data.username);})">自定义回调函数请求</a>
<br /><br />
<a href="javascript:ajaxLoader.show('<?php echo $this->createUrl('demo/ajax',array('display'=>1)); ?>','resultData')">加载数据到下面的区域</a> 
<div id="resultData">等待Ajax数据载入...</div>
</p>