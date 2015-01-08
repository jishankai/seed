<span id="buyNewGarden">
    <?php if ($showFlag) { ?>
        <?php if (isset($gardenPrice)) { ?>
            <?php if ($gardenPrice['gardenCount'] < GARDEN_MAXGARDENSIGN) { ?>
                <li class="empty ac">
                    <?php if ($gardenPrice['nowlevel'] < $gardenPrice['nextlevel']) { ?>      
                        <a class="a_btn1 a_btn02 mt20" href="#" disabled="disabled"><img src="themes/images/imga/ico2.png"><?php echo 'LEVEL' . $gardenPrice['nextlevel']; ?></a>
                    <?php } ?>
                    <?php if ($gardenPrice['nowlevel'] >= $gardenPrice['nextlevel'] && $gardenPrice['gold'] < $gardenPrice['price']) { ?>
                        <a class="a_btn1 a_btn01 mt20" href="#" onclick="ajaxLoader.get('<?php echo $this->createUrl('garden/AjaxGPrice'); ?>',addGarden)" disabled="disabled" style="color:red"><img src="themes/images/imgb/ico_7.png"><?php echo $gardenPrice['price']; ?> <front color="red"></a>
                    <?php } ?>
                    <?php if ($gardenPrice['nowlevel'] >= $gardenPrice['nextlevel'] && $gardenPrice['gold'] >= $gardenPrice['price']) { ?>
                        <a class="a_btn1 a_btn01 mt20" href="#" onclick="ajaxLoader.get('<?php echo $this->createUrl('garden/AjaxGPrice'); ?>',addGarden)"><img src="themes/images/imgb/ico_7.png"><?php echo $gardenPrice['price']; ?></a>
                        <?php } ?>
                </li>
            <?php } else { ?>
                </br></br></br>
            <?php } ?>
        <?php } ?>
    <?php } ?>
</span>