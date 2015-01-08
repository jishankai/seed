<div class="line"></div>
<?php $form = $this->beginWidget('CActiveForm', array('id'=>'reportForm',)); ?>
<?php echo $form->errorSummary($model); ?>
    <table class="t_report1">
        <tr>
            <td class="in_title">查询范围</td>
            <td colspan="2">
                <?php echo $form->radioButtonList($model, 'searchRange', array(0 => '全部')); ?>
            </td>
        </tr>
        <tr>
            <td class="in_title">查询类别</td>
            <td colspan="2">
                <?php echo $form->radioButtonList($model, 'resultType', array(0 => 'ReputationLevel')); ?>
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

<?php 
    if (isset($data['report'])) { 
        $result = $data['report']['result'];
?>
    <div class="line"></div><div class="line"></div>
    <span>Total User Num:</span><span><?php echo $data['report']['userNum']; ?></span>
    <div class="line"></div><div class="line"></div>
    <table width="100px">
    	<tr class="t_title">
            <th width="40px">Level</th>
            <th width="60px">Num</th>
        </tr>
        <?php foreach ($result as $line) {?>
        <tr style="text-align:center;">
        	<td width="40px"><?php if (isset($line['level'])) echo $line['level']; else echo '&nbsp;';?></td>
        	<td width="60px"><?php if (isset($line['num'])) echo $line['num']; else echo '&nbsp;';?></td>
        </tr>
        <?php }?>
    </table>
<?php 
    } 
?>
