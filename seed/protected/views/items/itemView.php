<ul class="b_list_01  clearfix" style="overflow:hidden;">
    <?php
    if (isset($itemList) && !empty($itemList)) {
        $count = 0;
        ?>
        <?php
        foreach ($itemList as $itemId => $item) {
            $count++;
            ?>
            <li id ="li<?php echo $count; ?><?php echo $item['item']['id']; ?>" <?php if ($item['item']->category != 3) { ?> onclick="show_li(<?php echo $count; ?>,<?php echo $item['item']['id']; ?>);"<?php } ?>>
                <div><?php $num = ($item['pile'] == 0) ? $item['num'] : ITEM_MAXPILE; ?>
                    <em id ="em<?php echo $count; ?><?php echo $item['item']['id']; ?>">X<?php echo $num ?></em>
                    <?php if ($item['item']->category == 3) { ?>
                        <a id ="href<?php echo $count; ?><?php echo $item['item']['id']; ?>" onclick="use(<?php echo $count; ?>,<?php echo $item['item']['id']; ?>,<?php echo $item['item']->category; ?>);return false" href="#">
                        <?php } ?>
                        <img src="<?php echo $item['itemMeta']->getImagePath(); ?>" alt="">
                        <?php if ($item['item']->category == 3) { ?>
                        </a>
                    <?php } ?>
                    <?php if ($item['item']->category != 3) { ?>
                        <span>
                            <img src="themes/images/imgb/ico_7.png" alt="">
                            <?php echo $item['item']->sellPrice; ?>
                        </span>
                    <?php } else { ?>
                        <span style="background:none">
                        </span>
                    <?php } ?>
                    <b><?php echo $item['item']->name; ?></b>
                </div>
                <p>
                    <?php if ($item['item']->canSell == 1 && $item['item']->category != 3) { ?>
                        <a id ="href<?php echo $count; ?><?php echo $item['item']['id']; ?>" onclick="sellItem(<?php echo $count; ?>,<?php echo $item['item']['id']; ?>,<?php echo $item['item']->category; ?>,<?php echo $num ?>);return false" href="#" class="a_btn03"><img src="themes/images/imga/ico3.png"></a>
                    <?php } ?>
                    <?php if ($item['item']->category == 1) { ?>
                        <a id ="href<?php echo $count; ?><?php echo $item['item']['id']; ?>" onclick="give(<?php echo $count; ?>,<?php echo $item['item']['id']; ?>,<?php echo $item['item']->category; ?>);return false" href="#" class="a_btn03"><img src="themes/images/imga/ico6.png"></a>
                        <?php } ?>
                </p>
                <div>
                    <?php
                    if ($category == 1) {
                        for ($i = 1; $i <= 6; $i++)
                            echo ' <i>' . ($item['item']->type == $i ? '<img src="themes/images/imgb/ico_' . $i . '.png">' : '') . '</i> ';
                    }
                    ?>
                    <?php echo $item['item']->description; ?>
                </div>
            </li>
        <?php } ?>
    <?php } ?>
</ul>