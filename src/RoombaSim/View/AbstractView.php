<?php

namespace sat8bit\RoombaSim\View;

use sat8bit\RoombaSim\Roomba\Roomba;
use sat8bit\RoombaSim\Room\AbstractRoom;

abstract class AbstractView
{
    /**
     * @param int $step
     * @param Roomba $roomba
     * @param AbstractRoom $room
     */
    abstract public function render($step, Roomba $roomba, AbstractRoom $room);
}
