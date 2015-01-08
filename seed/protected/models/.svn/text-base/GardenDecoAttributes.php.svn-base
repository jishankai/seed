<?php

/**
 * Description of GardenAttributes
 *
 * @author lamar.zau
 */
class GardenDecoAttributes extends CBehavior {

    public function getSpecial($itemId) {
        $array = Util::loadconfig("GardenDeco");
        $result = array();

        foreach ($array as $arr) {
            if ($arr['itemId'] == $itemId) {
                $result['itemId'] = $arr['itemId'];
                $result['decoId1'] = $arr['decoId1'];
                $result['decoId2'] = $arr['decoId2'];
                break;
            }
        }
        return $result;
    }

    public function getBackGroundId($decoId, $type) {
        $array = Util::loadconfig("GardenDeco");
        $result = array();

        foreach ($array as $arr) {
            if ($arr[$type] == $decoId) {
                $result['itemId'] = $arr['itemId'];
                break;
            }
        }
        return $result;
    }

    public function getSpecialId($itemId, $type) {
        $array = Util::loadconfig("GardenDeco");
        $result = array();

        foreach ($array as $arr) {
            if ($arr['itemId'] == $itemId) {
                if ($type == 1) {
                    $result['decoId'] = $arr['decoId1'];
                } else if ($type == 2) {
                    $result['decoId'] = $arr['decoId2'];
                }
                break;
            }
        }
        return $result;
    }

}

?>