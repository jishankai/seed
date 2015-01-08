<div style="width:100%; float:left;">
<h1>Model Documents</h1>
<?php foreach( $methodData as $className=>$method ) { ?> 
    <a href="#<?php echo $className; ?>" style="display:block; min-width:100px; float:left; margin:5px;"><?php echo $className; ?></a> 
<?php } ?>
<p style="clear:both;">
<?php foreach($methodData as $className=>$methods ) {?>
    <br />
    <a name="<?php echo $className; ?>"></a> 
    <h2><?php echo $className; ?></h2>
<ul>
	<?php foreach($methods as $name=>$comment ) {?>
	<li>
    <span class="blue"><?php echo $className.'->'.$name; ?>  </span>
	<tt><?php echo nl2br($comment); ?></tt> 
    <br />
	</li>
	<?php } ?> 
</ul>
<?php } ?> 

</p>
</div>
