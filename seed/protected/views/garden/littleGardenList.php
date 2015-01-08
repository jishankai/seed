<div class="b_con_01 b_bg_03" id="b_bg">
    <div class="pr01"><span class="a_bar1"><?php echo Yii::t('Garden', 'player gardenList'); ?></span></div>
    <a href="#" id ="littleGardenCloseBtn"class="a_btn04"></a>
    <div class="a_scroll pr">
        <div class="scroll_top"></div>
        <div class="a_list1" id="wrapper">
            <ul id="scrolldemo">
                <?php foreach ($allGardens as $garden) { ?>  
                    <li class="gardenArea" gardenId="<?php echo $garden->gardenId; ?>">
                        <h3>
                            <?php $backGround = (int) $garden->backGround - MIN_BACKGROUDID + 1; ?>
                            <span class="<?php echo $backGround < GARDEN_MAXGARDENSIGN ? 'num0' . $backGround : 'num' . $backGround; ?>" ><img src="images/smallItem/<?php echo $garden->gardenSign; ?>.png"/></span>
                            <span><img src="themes/images/imga/ico10.png" /><?php echo $garden->decoExtraGrow; ?></span>
                            <i id ="i_<?php echo $garden->gardenId; ?>"></i>
                            <?php
                            if ($garden->favouriteFlag == 1) {
                                echo "<i class='a_ico4'></i>";
                            }
                            ?>
                        </h3>
                        <ul class="b_imgli01 clearfix" id="ulGraden<?php echo $garden->gardenId; ?>">
                            <?php foreach ($garden->seedArrayList as $seedId) { ?>
                                <?php if (!empty($seedId)) { ?>
                                    <?php $seed = Yii::app()->objectLoader->load('Seed', $seedId); ?>
                                    <?php if ($seed->growPeriod < $growPeriod) continue; ?>
                                    <li id ="liSeed<?php echo $seed->seedId; ?>" style="width: 65px;margin-left: 15px;">
                                        <a class="seedArea" id ="showSeed<?php echo $seed->seedId; ?>" href="#" seedId="<?php echo $seed->seedId; ?>">
                                            <strong id="seedImageArea<?php echo $seed->seedId; ?>" class="fl w80" style="margin-left: 3px;" >
                                            </strong>
                                        </a>
                                        <?php if ($seed->favouriteFlag == 1) { ?>
                                            <i class="a_ico5" id='a_ico5'></i>
                                        <?php } ?>
                                        <script>
                                            SeedUnit("seedImageArea<?php echo $seed->seedId; ?>",<?php echo $seed->getDisplayData(); ?>); 
                                        </script>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

            </ul>
        </div>
    </div>
    <script type="text/javascript">
<?php if ($this->actionType == REQUEST_TYPE_AJAX) { ?>
        $(document).ready(function(){
            Flipsnap('#scrolldemo', { position: 'y' });
        });
<?php } else { ?>
        LoadAction.push(function(){ 
            Flipsnap('#scrolldemo', { position: 'y' });
        });
        selectLittleGardenList.callback = '<?php echo $_REQUEST['callbackUrl']; ?>';
<?php } ?>

    $(document).ready(function(){
<?php if ($isSelectSeed) { ?>
            $('.seedArea').click(function(){
                selectLittleGardenList.over($(this).attr('seedId'));
            });
<?php } else { ?>
            $('.gardenArea').click(function(){
                selectLittleGardenList.over($(this).attr('gardenId'));
            });
<?php } ?>
    });
    $(document).ready(function(){
        $('#littleGardenCloseBtn').bind("click",function(){window.selectLittleGardenList.close();if( selectLittleGardenList.isNative )NativeApi.close();})
    }) ;
    </script>
</div>