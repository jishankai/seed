<?php
abstract class Cost extends CComponent
{
    private $name;
    private $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Returns cost name.
     * @return string name of cost.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns cost value.
     * @return integer value of cost.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Pay this cost.
     * @param integer $playerId player id to pay.
     */
    abstract public function pay($playerId);

    /**
     * If the player can afford this cost.
     * @param integer $playerId player id to pay for this cost.
     * @return boolean if affordable, true, otherwise false.
     */
    abstract public function isAffordable($playerId);
}

?>
