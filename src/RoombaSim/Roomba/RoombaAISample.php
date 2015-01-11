<?php

namespace sat8bit\RoombaSim\Roomba;

class RoombaAISample implements RoombaAIInterface
{
    /**
     * when hit.
     *
     * @param int $distance
     * @return Motion
     */
    public function hit($distance)
    {
        return new Motion(mt_rand(0, 360), 1000);
    }

    /**
     * when ran.
     *
     * @param int $distance
     * @return Motion
     */
    public function ran($distance)
    {
        return new Motion(0, 1000);
    }
}
