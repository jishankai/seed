<?php
/**
 * FamitsuCodeModule is the module class to support famitsu code campaign.
 */

class FamitsuCodeModule extends CWebModule
{
    /**
     * @var The id of database where famitusCode tables resident, default to 'db'.
     * This property can be configured in configure file.
     */
    public $dbID='db';

    /**
     * @var The time this campaign begins.
     * This property can be configured in configure file.
     */
    public $beginTime;

    /**
     * @var The time this campaign ends.
     * This property can be configured in configure file.
     */
    public $endTime;

    /**
     * @var The return url after the apply process is completed.
     * This property can be configured in configure file.
     */
    public $returnRoute;

    /**
     * @var The url where redirect to if user adopt apply.
     * This property can be configured in configure file.
     */
    public $backRoute;

    /**
     * @var The name of userId attribute in sesion or $_GET.
     * This property can be configured in configure file.
     */
    public $userIdName = 'userId';

    /**
     * @var The source to get userId, default to get from session, you can get it from url query parameters by setting this property to 
     * 'get'.
     * This property can be configured in configure file.
     */
    public $userIdSource = 'session';

    /**
     * @var The number of codes a user can apply, default to 1.
     * This property can be configured in configure file.
     */
    public $maxCodesPerUser = 100000;

    /**
     * @var The class which represent user of famitsuCode. It should implement interface IFamitsuUser.
     * This property can be configured in configure file.
     */
    public $userClass;

    /**
     * Init the module.
     */
    protected function init()
    {
        $this->setImport(array(
            'famitsuCode.models.*',
            'famitsuCode.components.*',
        ));
    }

    /**
     * Returns whether the campaign has begun and not ended.
     * @return boolean Whether the campaign is open.
     */
    public function isOpen()
    {    
        return ($this->hasBegun() && !$this->hasEnded());
    }

    /**
     * Returns whether the campaign has begun.
     * @return boolean whether the campaign has begun.
     */
    public function hasBegun()
    {
        $time = time();
        if(isset($this->beginTime)){
            $beginTime = new DateTime($this->beginTime);
            if($time < $beginTime->getTimestamp()){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

    /**
     * Returns whether the campaign has ended.
     * @return boolean whether the campaign has ended.
     */
    public function hasEnded()
    {
        $time = time();
        if(isset($this->endTime)){
            $endTime = new DateTime($this->endTime);
            if($time > $endTime->getTimestamp()){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }





}
