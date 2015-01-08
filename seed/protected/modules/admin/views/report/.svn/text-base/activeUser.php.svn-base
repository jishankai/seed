<div class="line"></div>
<?php $form = $this->beginWidget('CActiveForm', array('id' => 'reportForm',)); ?>
<?php echo $form->errorSummary($model); ?>
<table class="t_report1">
    <tr>
        <td class="in_title">查询类别</td>
        <td colspan="8">
            <?php echo $form->radioButtonList($model, 'resultTimeType', array(0 => 'day', 1 => 'month'), array('separator' => '&nbsp;&nbsp;&nbsp;&nbsp;')); ?>
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

<?php
if (isset($data['report'])) {
    $result = $data['report']['result'];
    $timeList = $data['report']['timeList'];
    $titleList = array_keys($result);
    ?>
    <div class="line"></div><div class="line"></div>
    <table width="<?php $width = count($titleList) * 60 + 80;
    echo $width; ?>px">
        <tr class="t_title">
            <th width="80px">TIME</th>
        <?php foreach ($titleList as $title) {
            echo '<th width="60px">' . (($title === 'app') ? $title : 'World' . $title) . '</th>';
        } ?>
        </tr>
            <?php foreach ($timeList as $timeTitle) { ?>
            <tr style="text-align:center;">
                <th><?php if ($model->resultTimeType == 0) {
            echo date('Y/m/d', $timeTitle);
        } else {
            echo date('Y/m', $timeTitle);
        } ?></th>
                <?php
                foreach ($titleList as $title) {
                    $num = 0;
                    if (isset($result[$title][$timeTitle])) {

                        $num = $result[$title][$timeTitle]['activePlayerNum'];
                    }
                    echo '<td>' . $num . '</td>';
                }
                ?>
            </tr>
    <?php } ?>
    </table>
    <?php
}
?>

<script>
    $(document).ready(function() {
        $('.startDate').datepicker({ dateFormat: 'yy/mm/dd', showOn: 'button', buttonImage: '<?php echo Yii::app()->request->baseUrl; ?>/images/calendar.gif', buttonImageOnly: true,numberOfMonths:[1,2], duration: 0 ,showCurrentAtPos: 1});
        $('.endDate').datepicker({ dateFormat: 'yy/mm/dd', showOn: 'button', buttonImage: '<?php echo Yii::app()->request->baseUrl; ?>/images/calendar.gif', buttonImageOnly: true,numberOfMonths:[1,2], duration: 0  });
    });
</script>
