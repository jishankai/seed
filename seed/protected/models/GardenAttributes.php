<?php

/**
 * Description of GardenAttributes
 *
 * @author lamar.zau
 */
class GardenAttributes extends CBehavior {

    public function getGardenPrice($gardenCount) {
        $arrayPrice = Util::loadconfig("GardenPrice");
        $price = array();

        foreach ($arrayPrice as $i => $arr) {
            if ($i == $gardenCount + 1) {
                $price['level'] = $arr['level'];
                $price['gold'] = $arr['gold'];
                $price['price'] = $arr['gold'];
                break;
            }
        }
        return $price;
    }

    public function getDecoId($name) {
        $array = Util::loadconfig("DecoItem");
        $id = 0;

        foreach ($array as $i => $arr) {
            if ($name == str_replace(substr($arr['image'], -4), '', $arr['image'])) {
                $id = $i;
                break;
            }
        }
        return $id;
    }

    public function getCupIdByType($type) {
        //获得Deco配置信息
        $array = Util::loadconfig("DecoItem");
        $CupId = array();

        //筛选出小类型的一类奖杯
        foreach ($array as $i => $arr) {
            if ($arr['category'] == 5 && $arr['type'] == $type) {
                $CupId[$i] = $i;
            }
        }
        return $CupId;
    }

}

?>