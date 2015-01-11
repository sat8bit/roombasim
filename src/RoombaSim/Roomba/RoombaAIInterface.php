<?php

namespace sat8bit\RoombaSim\Roomba;

interface RoombaAIInterface
{
    /**
     * when hit.
     *
     * @param int $distance
     * @return Motion
     */
    public function hit($distance);

    /**
     * when ran.
     *
     * @param int $distance
     * @return Motion
     */
    public function ran($distance);
}
