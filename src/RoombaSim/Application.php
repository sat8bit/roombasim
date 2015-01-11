<?php

namespace sat8bit\RoombaSim;

use sat8bit\RoombaSim\Room\AbstractRoom;
use sat8bit\RoombaSim\Roomba\Roomba;

class Application
{
    /**
     * @var AbstractRoom
     */
    private $room;

    /**
     * @var Roomba
     */
    private $roomba;

    /**
     * constructor.
     *
     * @param AbstractRoom $room
     * @param Roomba $roomba
     */
    public function __construct(AbstractRoom $room, Roomba $roomba)
    {
        $this->room = $room;
        $this->roomba = $roomba;
    }

    /**
     * run.
     */
    public function run($step = 100)
    {
        $room = $this->room;
        $roomba = $this->roomba;
        $count = 0;
        do {
            if ($room->has($roomba->getNextCoordinate())) {
                $roomba->forward();
                $room->clean($roomba->getCurrentCoordinate());
            }
            else {
                $roomba->hit();
            }

            if ($room->isClean()) {
                break;
            }
        }while ($count++ < $step);

        return $room->isClean() ? $count : false;
    }

    /**
     * run with disp.
     *
     * @param int $step
     * @param int $sleep micro second
     * @return mixed
     */
    public function runWithDisp($step = 100, $sleep = 50000)
    {
        $room = $this->room;
        $roomba = $this->roomba;
        $count = 0;
        do {
            $room->clean($roomba->getCurrentCoordinate());
            if ($room->has($roomba->getNextCoordinate())) {
                $roomba->forward();
            }
            else {
                $roomba->hit();
            }

            if ($room->isClean()) {
                break;
            }
            usleep($sleep);
            $roomba->disp();
            $room->disp($roomba->getCurrentCoordinate());
        }while ($count++ < $step);

        return $room->isClean() ? $count : false;
    }
}
