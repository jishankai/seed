<div class="line"></div>
<?php $form = $this->beginWidget('CActiveForm', array('id'=>'reportForm',)); ?>
<?php echo $form->errorSummary($model); ?>
    <table class="t_report1">
        <tr>
            <td class="in_title">查询范围</td>
            <td colspan="2">
                <?php echo $form->radioButtonList($model, 'searchRange', array(0 => '全部', 1 => 'UserId'), array('separator' => '&nbsp;&nbsp;&nbsp;&nbsp;')); ?>
                &nbsp;
                <?php echo $form->textField($model,'searchUserId', array('size' => "10px")); ?>
            </td>
        </tr>
        <tr>
            <td class="in_title">出力方式</td>
            <td>
                <!--<input checked="true" value="0" name="mkcsv" type="radio" id="mk_1"></input><label for="mk_1">HTML</label>-->
            </td>
            <td width="20%" class="su_title_2">
                <?php echo CHtml::submitButton('実行'); ?>
            </td>
        </tr>
    </table>
<?php $this->endWidget(); ?>

<?php if (isset($data['gold'])) { ?>
    <div class="line"></div><div class="line"></div>
	<?php if ($data['gold']['type']==0) {?>
    <table width="760px">
        <tr class="t_title">
            <td>充值Gold总数</td>
            <td>充值人数</td>
            <td>人均充值Gold</td>
            <td>总人均充值Gold</td>
        </tr>
		<tr style="text-align:center;">
        <?php foreach ($data['gold']['pgold'] as $line) { ?>
            <td><?php echo number_format($line['pGoldsum'],2); ?></td>
            <td><?php echo number_format($line['pGoldcount']); ?></td>
			<td><?php if ($line['pGoldcount']==0) {echo number_format(0,2);} else {echo number_format($line['pGoldsum']/$line['pGoldcount'],2);} ?></td>
			<td><?php if ($data['gold']['num']==0) {echo number_format(0,2);} else {echo number_format($line['pGoldsum']/$data['gold']['num'],2);} ?></td>
        <?php } ?>
        </tr>  
		<tr class="t_title">
            <td>赠送Gold总数</td>
            <td>赠送人数</td>
            <td>人均赠送Gold</td>
            <td>总人均赠送Gold</td>
        </tr>
		<?php foreach ($data['gold']['sgold'] as $line) { ?>
			<td><?php echo number_format($line['sGoldsum'],2); ?></td>
            <td><?php echo number_format($line['sGoldcount']); ?></td>
			<td><?php if ($line['sGoldcount']==0) {echo number_format(0,2);} else {echo number_format($line['sGoldsum']/$line['sGoldcount'],2);} ?></td>
			<td><?php if ($data['gold']['num']==0) {echo number_format(0,2);} else {echo number_format($line['sGoldsum']/$data['gold']['num'],2);} ?></td>
		<?php } ?>      
    </table>
	<?php }else { ?>
	<table width="760px">
        <tr class="t_title">
			<td>用户Id</td>
            <td>充值Gold</td>
            <td>赠送Gold</td>
            <td>最后更新时间</td>
        </tr>
		<?php foreach ($data['gold']['ugold'] as $line) { ?>
		<tr style="text-align:center;">
			<td><?php echo $line['userId']; ?></td>
			<td><?php echo $line['purchaseGold']; ?></td>
            <td><?php echo $line['systemGold']; ?></td>
			<td><?php echo $line['updateTime']; ?></td>
		</tr> 
		<?php } ?>       
    </table>
	<?php } ?>
<?php } ?>
