<?php
/**
 * Visual Seed
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-06-08
 * @package Seed
 **/
class VisualSeed extends Seed
{
    protected function saveData($attributes = array())
    {
        return ;
    }

    public function canBreed()
    {
        if (intval($this->seedId/100)==10) {
            return true;
        } else {
            return false;
        }
    }
}
?>
