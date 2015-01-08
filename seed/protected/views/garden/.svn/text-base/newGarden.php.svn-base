<?php $garden = Yii::app()->objectLoader->load('Garden', $newGardenId); ?>
<li onclick="selectMoveGarden(<?php echo $garden->gardenId; ?>)">
    <h3 id="h3_<?php echo $garden->gardenId; ?>">
        <a class ="gardenHref"onclick="selectGarden(<?php echo $garden->gardenId; ?>,<?php echo $garden->playerId ?>,<?php echo $garden->gardenSign ?>);" href="#">
            <?php $backGround = (int) $garden->backGround - MIN_BACKGROUDID + 1; ?>
            <span class="<?php echo $backGround < GARDEN_MAXGARDENSIGN ? 'num0' . $backGround : 'num' . $backGround; ?>" ><img src="images/smallItem/<?php echo $garden->gardenSign; ?>.png"/></span>
        </a>
        <span><img src="themes/images/imga/ico10.png" /><?php echo $garden->decoExtraGrow; ?></span>
        <input type="radio" id ="chk_<?php echo $garden->gardenId; ?>" name="radio" value="<?php echo $garden->gardenId; ?>" style="display:none"/><br />
        <i id ="i_<?php echo $garden->gardenId; ?>"></i>
        <?php
        if ($garden->favouriteFlag == 1) {
            echo "<i class='a_ico4' id='a_ico4'></i>";
        }
        ?>
    </h3>
    <ul class="b_imgli01 clearfix" id="ulGraden<?php echo $garden->gardenId; ?>">
    </ul>
</li>
<input type="hidden" name="newGardenId" id="newGardenId" value="<?php echo $newGardenId; ?>">