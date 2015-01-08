<?php if($pageNum!=0){ ?>
	<div>
	<?php if ($currentPage!=1) {?>
    <span class='scale'><a href="#_self" onclick="return false;" id="previous">Previous</a></span>
	<?php }else {?>
	<span class='scale'><a href="#_self" onclick="return false;" id="previous" style="display:none">Previous</a></span>
	<?php } ?>
    <span>
		<?php for($i=1; $i<=$pageNum; $i++){ ?>
			<?php if($i%PAGE_MENU_NUM == 1){ echo "<div style='display:none'>";} ?>
                <em <?php if($i == $currentPage){ ?> class="on" <?php } ?> id="em<?php echo $id; ?><?php echo $i; ?>"></em>
            <?php if($i%PAGE_MENU_NUM == 0 || $i == $pageNum){ echo "</div>";} ?>
        <?php } ?>
        <samp id="samp<?php echo $id; ?>"><?php echo $currentPage ?></samp>
	</span>
	<?php if ($currentPage<$pageNum) {?>
	<span class='scale'><a href="#_self" onclick="return false;" id="next">Next</a></span>
	<?php } else {?>
	<span class='scale'><a href="#_self" onclick="return false;" id="next" style="display:none">Next</a></span>
	<?php } ?>
	</div>
<?php } ?>

<?php if($pageNum!=0 && $id==''){ ?>
    <input type="hidden" id="pageNum" value="<?php echo $pageNum; ?>" />
    <input type="hidden" id="currentPage" value="<?php echo $currentPage; ?>" />
<?php } ?>
<script  type="text/javascript">
    $(document).ready(function(){
        var currentPage = $("#currentPage").attr('value');
        $("#em<?php echo $id; ?>"+currentPage).parent().show();
        
    });
    
    
</script>

