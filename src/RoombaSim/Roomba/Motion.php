<?php

namespace sat8bit\RoombaSim\Roomba;

class Motion
{
    /**
     * @var int
     */
    protected $lotate;

    /**
     * @var int
     */
    protected $distance;

    public function __construct($lotate, $distance)
    {
        if (!is_int($lotate) || !is_int($distance)) {
            throw new \InvalidArgumentException;
        }
        $this->lotate = $lotate;
        $this->distance = $distance;
    }

    public function getLotate()
    {
        return $this->lotate;
    }

    public function getDistance()
    {
        return $this->distance;
    }
}
