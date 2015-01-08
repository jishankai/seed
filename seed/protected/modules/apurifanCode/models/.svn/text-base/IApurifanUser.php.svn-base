<?php
interface IApurifanUser
{
    /**
     * Returns the id of user.
     */
    public function getUserId();

    /**
     * Returns the array of rewards user could get.
     */
    public function getRewards();

    /**
     * Apply code and receive rewards.
     */
    public function applyCode($code);

    /**
     * Returns the number of remained codes this user can apply.
     * @return integer the number of remained codes this user can apply.
     */
    public function getRemainedCodeNum();
}
