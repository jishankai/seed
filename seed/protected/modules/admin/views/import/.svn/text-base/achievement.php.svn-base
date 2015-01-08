<div>
<div>成就的数据文件为 protected/data/import/achievement.xls 请手动更新，然后执行导入操作</div>
<div>*请输入验证码然后点击导入</div>

<div id="result"></div>
<input type="text" id="verifyCode">
<button id="submit">导入</button>
<?php $this->widget('CCaptcha')?>
<SCRIPT type="text/javascript">
jQuery('#submit').bind('click', function(){
	var verifyCode = $('#verifyCode').val();
    ajaxLoader.get("<?php echo $this->createUrl('import/achievement') ?>&verifyCode="+encodeURIComponent(verifyCode),function(data){
        if (data.isSuccess) {
            $('#result').html('Success!');
        }
    });
});

</SCRIPT>
</div>
