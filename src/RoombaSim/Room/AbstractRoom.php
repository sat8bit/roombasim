<?php

namespace sat8bit\RoombaSim\Room;

use sat8bit\RoombaSim\Coordinate;

abstract class AbstractRoom
{
    const OBSTACLE = false;
     
    /**
     * @var array
     */
    protected $map = array();
    
    /**
     * clean.
     *
     * @return boolean
     */
    public function isClean()
    {
        foreach ($this->map as $y => $line) {
            foreach ($line as $x => $v) {
                if ($this->map[$y][$x] > 0) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * clean.
     *
     * @param Coordinate $coordinate
     */
    public function clean(Coordinate $coordinate)
    {
        if (!$this->has($coordinate)) {
            throw new \InvalidArgumentException;
        }

        $dirt = $this->map[$coordinate->getY()][$coordinate->getX()] - 1;

        $this->map[$coordinate->getY()][$coordinate->getX()] = $dirt < 0 ? 0 : $dirt;
    }

    /**
     * has.
     *
     * @param Coordinate $coordinate
     * @return boolean
     */
    public function has(Coordinate $coordinate)
    {
        $x = $coordinate->getX();
        $y = $coordinate->getY();

        if (!isset($this->map[$y])) {
            return false;
        }

        if (!isset($this->map[$y][$x])) {
            return false;
        }

        return $this->map[$y][$x] !== self::OBSTACLE;
    }

    /**
     * getMap.
     *
     * @return array
     */
    public function getMap()
    {
        return $this->map;
    }
}
