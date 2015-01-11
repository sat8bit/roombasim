<?php

namespace sat8bit\RoombaSim\Room;

use sat8bit\RoombaSim\Coordinate;

class Room
{
    const OBSTACLE = false;
     
    const DISP_RUMBA = "()";

    const DISP_OBSTACLE = "XX";

    /**
     * @var array
     */
    protected $map = array();
    
    /**
     * construct.
     * 
     * @param int $height
     * @param int $width
     */
    public function __construct($height, $width, $dirt = 1)
    {
        if (!is_int($height) || !is_int($width) || !is_int($dirt)) {
            throw new \InvalidArgumentException;
        }

        for ($i = 1; $i <= $height; $i++) {
            $line = array();
            for ($j = 1; $j <= $width; $j++) {
                $line[$j] = $dirt;
            }
            $this->map[$i] = $line;
        }
    }

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
     * @param Coordinate $coordinate
     */
    public function disp(Coordinate $coordinate)
    {
        $disp = "";
        foreach ($this->map as $y => $line) {
            $disp .= "|";
            foreach ($line as $x => $v) {
                if ($v === self::OBSTACLE) {
                    $disp .= self::DISP_OBSTACLE;
                } else if ($x == (int)$coordinate->getX() && $y == (int)$coordinate->getY()) {
                    $disp .= self::DISP_RUMBA;
                } else {
                    $disp .= $this->dispDirt($v);
                }
            }
            $disp .= "|";
            $disp .= "\n";
        }

        echo $disp;
    }

    protected function dispDirt($dirt) {
        switch($dirt) {
            case 0: return "  ";
            case 1: return " .";
            case 2: return "'.";
            case 3: return ";'";
            default: return ";;";
        }
    }

}
