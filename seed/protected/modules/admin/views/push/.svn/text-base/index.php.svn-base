<div class="line"></div>
<?php $form = $this->beginWidget('CActiveForm', array('id'=>'pushForm',)); ?>
<?php echo $form->errorSummary($model); ?>
    <table class="t_report1">
        <tr>
            <td>查询范围</td>
            <td colspan="3">
                <?php echo $form->radioButtonList($model, 'searchUserRange', array(0 => '全部', 1 => 'UserId'), array('separator' => '&nbsp;&nbsp;&nbsp;&nbsp;')); ?>
                &nbsp;&nbsp;
                <?php echo $form->textField($model,'searchUserId', array('size' => "10px")); ?>
            </td>
        </tr>
        <tr>
            <td>期間</td>
            <td colspan="1"><?php echo $form->labelEx($model, '發送日'); ?>&nbsp;<?php echo $form->textField($model, 'sendDate'); ?></td>
        </tr>
        <tr>
        	<td>驗證碼</td>
        	<td>
        		<?php $this->widget('CCaptcha',array('showRefreshButton'=>false,'clickableImage'=>true,'imageOptions'=>array('alt'=>'点击换图','title'=>'点击换图','style'=>'cursor:pointer'))); ?>
        		&nbsp;&nbsp;
        		<?php echo $form->textField($model,'verifyCode', array('size' => "10px")); ?> 
        	</td>
        	<?php echo $form->error($model,'verifyCode'); ?>
        </tr>
        <tr>
        	<td>內容</td>
        	<td colspan="2"><?php echo $form->textArea($model,'content', array('rows' => 10,'cols' => 50)); ?></td>
        </tr>
        <tr>
            <td colspan="2">出力方式</td>
            <td>
                <input type="button" value="実行" onclick="PushFormCommit()">
            </td>
        </tr>
    </table>
<?php $this->endWidget(); ?>
<script>
	function PushFormCommit() {
		if (window.confirm("确认推送内容？")) {
				pushForm.submit();
		}
	}
</script>