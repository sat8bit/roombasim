<?php

namespace sat8bit\RoombaSim;

class Coordinate
{
    /**
     * @var double
     */
    protected $x;

    /**
     * @var double
     */
    protected $y;

    /**
     * constructor.
     *
     * @param double $x
     * @param double $y
     */
    public function __construct($x, $y)
    {
        if (!is_numeric($x) || !is_numeric($y)) {
            throw new \InvalidArgumentException;
        }
        $this->x = (double)$x;
        $this->y = (double)$y;
    }

    /**
     * getX.
     *
     * @return double
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * getY.
     *
     * @return double
     */
    public function getY()
    {
        return $this->y;
    }
}
