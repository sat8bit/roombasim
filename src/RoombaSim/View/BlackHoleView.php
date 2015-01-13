<?php

namespace sat8bit\RoombaSim\View;

use sat8bit\RoombaSim\Roomba\Roomba;
use sat8bit\RoombaSim\Room\AbstractRoom;

class BlackHoleView extends AbstractView
{
    /**
     * @param int $step
     * @param Roomba $roomba
     * @param AbstractRoom $room
     */
    public function render($step, Roomba $roomba, AbstractRoom $room)
    {
    }
}
