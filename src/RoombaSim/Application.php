<?php

namespace sat8bit\RoombaSim;

use sat8bit\RoombaSim\Room\AbstractRoom;
use sat8bit\RoombaSim\Roomba\Roomba;
use sat8bit\RoombaSim\View\AbstractView;

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
     * @var AbstractView
     */
    private $view;

    /**
     * constructor.
     *
     * @param AbstractRoom $room
     * @param Roomba $roomba
     */
    public function __construct(AbstractRoom $room, Roomba $roomba, AbstractView $view)
    {
        $this->room = $room;
        $this->roomba = $roomba;
        $this->view = $view;
    }

    /**
     * run.
     */
    public function run($step = 100)
    {
        $room = $this->room;
        $roomba = $this->roomba;
        $count = 0;

        while ($count++ < $step) {
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

            $this->view->render($count, $roomba, $room);
        }

        return $room->isClean() ? $count : false;
    }
}
