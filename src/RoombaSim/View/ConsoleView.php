<?php

namespace sat8bit\RoombaSim\View;

use sat8bit\RoombaSim\Roomba\Roomba;
use sat8bit\RoombaSim\Room\AbstractRoom;

class ConsoleView extends AbstractView
{
    const DISP_RUMBA = "()";

    const DISP_OBSTACLE = "XX";

    /**
     * @var boolean
     */
    protected $isFirstCall = true;

    /**
     * @param int $step
     * @param Roomba $roomba
     * @param AbstractRoom $room
     */
    public function render($step, Roomba $roomba, AbstractRoom $room)
    {
        $coordinate = $roomba->getCurrentCoordinate();
        $map = $room->getMap();

        $disp = "";
        foreach ($map as $y => $line) {
            $disp .= "|";
            foreach ($line as $x => $v) {
                if ($v === AbstractRoom::OBSTACLE) {
                    $disp .= self::DISP_OBSTACLE;
                } else if ($x == (int)$coordinate->getX() && $y == (int)$coordinate->getY()) {
                    $disp .= self::DISP_RUMBA;
                } else {
                    $disp .= $this->getDispDirt($v);
                }
            }
            $disp .= "|";
            $disp .= "\n";
        }

        if ($this->isFirstCall) {
            $this->isFirstCall = false;
        } else {
            echo "\x1b[".(2+count($map))."A";
        }

        echo "step($step)\n";
        echo "\x1b[K";
        echo (string)$roomba;
        echo $disp;
        usleep(50000);
    }

    /**
     * @param int $dirt
     * @return string
     */
    protected function getDispDirt($dirt)
    {
        switch($dirt) {
            case 0: return "  ";
            case 1: return " .";
            case 2: return "'.";
            case 3: return ";'";
            default: return ";;";
        }
    }
}
