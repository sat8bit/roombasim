<?php

namespace sat8bit\RoombaSim\Roomba;

class Direction
{
    /**
     * @var int
     */
    protected $direction;

    /**
     * constructor.
     *
     * @param int $direction
     */
    public function __construct($direction = 0)
    {
        if (!is_int($direction)) {
            throw new \InvalidArgumentException;
        }
        $this->direction = $direction;
    }

    /**
     * get direction.
     *
     * @return int
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * lotate.
     *
     * @param int $deg
     * @return $int
     */
    public function lotate($deg)
    {
        if (!is_int($deg)) {
            throw new \InvalidArgumentException;
        }

        return $this->direction = ($this->direction + $deg) % 360;
    }
}
