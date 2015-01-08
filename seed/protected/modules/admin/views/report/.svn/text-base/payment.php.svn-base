<div class="line"></div>
<?php $form = $this->beginWidget('CActiveForm', array('id'=>'reportForm',)); ?>
<?php echo $form->errorSummary($model); ?>
    <table class="t_report1">
        <tr>
            <td class="in_title">查询范围</td>
            <td colspan="8">
                <?php echo $form->radioButtonList($model, 'searchUserRange', array(0 => '全体', 1 => 'userId'), array('separator' => '&nbsp;&nbsp;&nbsp;&nbsp;')); ?>
                &nbsp;
                <?php echo $form->textField($model,'searchUserId', array('size' => "10px")); ?>
            </td>
        </tr>
        <tr>
            <td class="in_title">查询类别</td>
            <td colspan="8">
                <?php echo $form->radioButtonList($model, 'resultTimeType', array(0 => '全部', 1 => '日别', 2 => '月别'), array('separator' => '&nbsp;&nbsp;&nbsp;&nbsp;')); ?>
            </td>
        </tr>
        <tr>
            <td rowspan=3 class="in_title">期間</td>
            <td colspan=4><?php echo $form->labelEx($model, '開始日'); ?>&nbsp;<?php echo $form->textField($model, 'startDate', array('class' => 'startDate')); ?></td>
            <td colspan=4><?php echo $form->labelEx($model, '終了日'); ?>&nbsp;<?php echo $form->textField($model, 'endDate', array('class' => 'endDate')); ?></td>
        </tr>
        <tr style="text-align:center;">
            <td style="text-align:left;">日:</td>
            <td><a href="javascript:void(0);" onclick="SetDate.setDay(0);" >当日</a></td>
            <td><a href="javascript:void(0);" onclick="SetDate.setDay(1)" >1日前</a></td>
            <td><a href="javascript:void(0);" onclick="SetDate.setDay(2)" >2日前</a></td>
            <td><a href="javascript:void(0);" onclick="SetDate.setDay(3)" >3日前</a></td>
            <td><a href="javascript:void(0);" onclick="SetDate.setDay(4)" >4日前</a></td>
            <td><a href="javascript:void(0);" onclick="SetDate.setDay(5)" >5日前</a></td>
            <td><a href="javascript:void(0);" onclick="SetDate.setDay(6)" >6日前</a></td>
        </tr>
        <tr style="text-align:center;">
            <td style="text-align:left;">月:</td>
            <td><a href="javascript:void(0);" onclick="SetDate.setMonth(0)" >当月</a></td>
            <td><a href="javascript:void(0);" onclick="SetDate.setMonth(1)" >1月前</a></td>
            <td><a href="javascript:void(0);" onclick="SetDate.setMonth(2)" >2月前</a></td>
            <td><a href="javascript:void(0);" onclick="SetDate.setMonth(3)" >3月前</a></td>
            <td><a href="javascript:void(0);" onclick="SetDate.setMonth(4)" >4月前</a></td>
            <td><a href="javascript:void(0);" onclick="SetDate.setMonth(5)" >5月前</a></td>
            <td><a href="javascript:void(0);" onclick="SetDate.setMonth(6)" >6月前</a></td>
        </tr>
        <tr>
            <td class="in_title">出力方式</td>
            <td colspan="6">
                <!--<input checked="true" value="0" name="mkcsv" type="radio" id="mk_1"></input><label for="mk_1">HTML</label>-->
            </td>
            <td colspan="2" class="su_title_2">
                <?php echo CHtml::submitButton('実行'); ?>
            </td>
        </tr>
    </table>
<?php $this->endWidget(); ?>

<?php if (isset($data['result'])) { ?>
    <div class="line"></div><div class="line"></div>
    <table width="760px">
        <tr class="t_title">
            <td>时间</td>
            <td>充值金额</td>
            <td>充值笔数</td>
            <td>充值Gold</td>
            <td>系统赠送Gold</td>
        </tr>
        <?php foreach ($data['result'] as $line) { 
                if ($line['num'] == 0) {
                    continue;
                }
        ?>
        <tr style="text-align:center;">
            <td><?php if (isset($line['timeStr'])) {echo $line['timeStr'];} else {echo '&nbsp;';} ?></td>
            <td><?php echo $line['money']; ?></td>
            <td><?php echo $line['num']; ?></td>
            <td><?php echo $line['purchaseGold']; ?></td>
            <td><?php echo $line['systemGold']; ?></td>
        </tr>
        <?php } ?>
		<?php if (isset($data['total'])) { ?>
			<?php foreach ($data['total'] as $line) { 
                if ($line['num'] == 0) {
                    continue;
                }
        	?>
        	<tr style="text-align:center;" class="t_title">
				<td>总计</td>
            	<td><?php echo $line['money']; ?></td>
            	<td><?php echo $line['num']; ?></td>
            	<td><?php echo $line['purchaseGold']; ?></td>
            	<td><?php echo $line['systemGold']; ?></td>
        	</tr>
        	<?php } ?>
		<?php } ?>
    </table>
<?php } ?>

<script>
    $(document).ready(function() {
        $('.startDate').datepicker({ dateFormat: 'yy/mm/dd', showOn: 'button', buttonImage: '<?php echo Yii::app()->request->baseUrl; ?>/images/calendar.gif', buttonImageOnly: true,numberOfMonths:[1,2], duration: 0 ,showCurrentAtPos: 1});
        $('.endDate').datepicker({ dateFormat: 'yy/mm/dd', showOn: 'button', buttonImage: '<?php echo Yii::app()->request->baseUrl; ?>/images/calendar.gif', buttonImageOnly: true,numberOfMonths:[1,2], duration: 0  });
    });
</script>
