<?php

namespace sat8bit\RoombaSim\Roomba;

use sat8bit\RoombaSim\Coordinate;

class Roomba
{
    /**
     * @var Coordinate
     */
    protected $current;

    /**
     * @var Direction
     */
    protected $direction;

    /**
     * @var RoombaAIInterface
     */
    private $ai;

    /**
     * @var int
     */
    private $distance = 0;

    /**
     * @var int
     */
    private $reserveDistance = null;

    /**
     * constructor.
     *
     * @param Coordinate $current
     * @param RoombaAIInterface $ai
     */
    public function __construct(Coordinate $current, Direction $direction, RoombaAIInterface $ai)
    {
        $this->current = $current;
        $this->direction = $direction;
        $this->ai = $ai;
    }

    /**
     * getCurrentCoordinate
     *
     * @return Coordinate
     */
    public function getCurrentCoordinate()
    {
        return $this->current;
    }

    /**
     * getNextCoordinate
     *
     * @return Coordinate
     */
    public function getNextCoordinate()
    {
        // 指定距離走行後の場合
        if ($this->isRanDistance()) {
            //現在地が次の座標になる
            return $this->current;
        }

        $d = $this->direction->getDirection();
        return new Coordinate($this->current->getX() + cos(deg2rad($d)), $this->current->getY() + sin(deg2rad($d)));
    }

    /**
     * next.
     */
    public function forward()
    {
        // 指定距離走行後の場合
        if ($this->isRanDistance()) {
            // 走りきったときの挙動を取得
            $motion = $this->ai->ran($this->distance);
            if (!($motion instanceof Motion)) {
                throw new \LogicException(get_class($ai) . "::ran($distance) is not return Motion.");
            }
            $this->setMotion($motion);
        } else {
            $this->current = $this->getNextCoordinate();
            $this->distance++;
        }
    }

    /**
     * hit.
     */
    public function hit()
    {
        $motion = $this->ai->hit($this->distance);
        if (!($motion instanceof Motion)) {
            throw new \LogicException(get_class($ai) . "::hit($distance) is not return Motion.");
        }
        $this->setMotion($motion);
    }

    /**
     * ran.
     */
    public function isRanDistance()
    {
        return !is_null($this->reserveDistance) && $this->reserveDistance <= $this->distance;
    }

    /**
     * set motion
     *
     * @param Motion $motion
     */
    protected function setMotion(Motion $motion)
    {
        $this->direction->lotate($motion->getLotate());
        $this->reserveDistance = $motion->getDistance();
        $this->distance = 0;
    }

    /**
     * disp
     */
    public function __toString()
    {
        return "R({$this->distance}), C({$this->current->getX()}, {$this->current->getY()}), D({$this->direction->getDirection()})\n";
    }
}
