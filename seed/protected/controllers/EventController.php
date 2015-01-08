<?php
/**
 * Event Controller
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-07-26
 * @package Seed
 **/
class EventController extends Controller
{
    public function actionIndex()
    {
        //$this->redirect($this->createUrl('famitsuCode/default/index'));
        $this->display('index');
    }

    /*
    public function actionStart()
    {
       $this->redirect($this->createUrl('famitsuCode/default/index', array('playerId'=>$this->playerId)));
    }
     */
}
?>
