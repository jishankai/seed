<?php foreach ($allGardens as $garden) { ?> 
    <li>
        <h3>
            <a onclick="selectGarden(<?php echo $garden->gardenId; ?>,<?php echo $garden->playerId ?>,<?php echo $garden->gardenSign ?>);" href="#">
                <span class="num0<?php echo $garden->gardenSign ?>" ><img src="themes/images/imga/0<?php echo $garden->gardenSign ?>.png"/></span>
            </a>
            <span><img src="themes/images/imga/ico10.png" /><?php echo $garden->decoExtraGrow; ?></span>
            <input type="radio" id ="chk_<?php echo $garden->gardenId; ?>" name="radio" value="<?php echo $garden->gardenId; ?>" style="display:none"/><br />
            <i id ="i_<?php echo $garden->gardenId; ?>"></i>
            <?php
            if ($garden->favouriteFlag == 1) {
                echo "<i class='a_ico4'></i>";
            }
            ?>
        </h3>
        <?php foreach ($garden->seedArrayList as $seedId) { ?>
            <?php if (!empty($seedId)) { ?>
                <?php $seed = Yii::app()->objectLoader->load('Seed', $seedId); ?>
                <a id ="showSeed<?php echo $seed->seedId; ?>" onclick="SeedAction.showInfo(<?php echo $seed->seedId; ?>);" href="#">
                    <strong id="seedImageArea<?php echo $seed->seedId; ?>" class="fl w80" style="margin-top:-10px;" onclick="divClick(<?php echo $seed->seedId; ?>);" >
                        <?php if ($seed->favouriteFlag == 1) { ?>
                            <i class="a_ico5"></i>
                        <?php } ?>
                        <?php if ($showFlag) { ?>
                            <input type="radio" id ="chkSeed_<?php echo $seed->seedId; ?>" name="radioSeed" value="<?php echo $seed->seedId; ?>" style="display:none"/><br />
                        <?php } ?>
                    </strong>
                </a>
                <script>
                    SeedUnit("seedImageArea<?php echo $seed->seedId; ?>",<?php echo $seed->getDisplayData(); ?>); 
                </script>
            <?php } ?>
        <?php } ?>
        <?php if ($garden->fosterList != '') { ?>  
            <?php $seed1 = Yii::app()->objectLoader->load('Seed', $garden->fosterList); ?>
            <a id ="showSeed<?php echo $garden->fosterList; ?>" onclick="SeedAction.showInfo(<?php echo $garden->fosterList; ?>);" href="#">
                <strong id="seedImageArea<?php echo $garden->fosterList; ?>" class="fl w80" style="margin-top:-10px;" onclick="divClick(<?php echo $garden->fosterList; ?>);" >
                    <?php if ($seed1->favouriteFlag == 1) { ?>
                        <i class="a_ico5"></i>
                    <?php } ?>
                    <?php if ($showFlag) { ?>
            <!--                                    <a id="btn<?php //echo $garden->fosterList;          ?>" onclick="seedBack(<?php //echo $garden->fosterList;          ?>);" href="#">打回</a>-->
                    <?php } ?>
                </strong>
            </a>
            <script>
                SeedUnit("seedImageArea<?php echo $garden->fosterList; ?>",<?php echo $seed1->getDisplayData(); ?>); 
            </script>
        <?php } ?>
    </li> 
<?php } ?>

<?php if ($showFlag) { ?>
    <?php if ($gardenPrice['gardenCount'] < GARDEN_MAXGARDENSIGN) { ?>
        <li class="empty ac">
            <?php if ($gardenPrice['nowlevel'] < $gardenPrice['nextlevel']) { ?>      
                <a class="a_btn1 a_btn02 mt20" href="#" onclick="ajaxLoader.get('<?php echo $this->createUrl('garden/AjaxGPrice'); ?>',addGarden)" disabled="disabled"><img src="themes/images/imga/ico2.png"><?php echo 'LEVEL' . $gardenPrice['nextlevel']; ?></a>
            <?php } ?>
            <?php if ($gardenPrice['nowlevel'] >= $gardenPrice['nextlevel'] && $gardenPrice['gold'] < $gardenPrice['price']) { ?>
                <a class="a_btn1_on mt20" href="#" onclick="ajaxLoader.get('<?php echo $this->createUrl('garden/AjaxGPrice'); ?>',addGarden)" disabled="disabled" style="color:red"><img src="themes/images/imgb/ico_7.png"><?php echo $gardenPrice['price']; ?> <front color="red"></a>
            <?php } ?>
            <?php if ($gardenPrice['nowlevel'] >= $gardenPrice['nextlevel'] && $gardenPrice['gold'] >= $gardenPrice['price']) { ?>
                <a class="a_btn1 a_btn01 mt20" href="#" onclick="ajaxLoader.get('<?php echo $this->createUrl('garden/AjaxGPrice'); ?>',addGarden)"><img src="themes/images/imgb/ico_7.png"><?php echo $gardenPrice['price']; ?></a>
                <?php } ?>
        </li>
    <?php } else { ?>
        </br></br></br>
    <?php } ?>
<?php } ?>