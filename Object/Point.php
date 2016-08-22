<?php

namespace FLE\Bundle\PostgresqlTypeBundle\Object;

class Point
{
    /**
     * @var float
     */
    protected $x;

    /**
     * @var float
     */
    protected $y;

    public function __construct ($x = null, $y = null)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return float
     */
    public function getX ()
    {
        return $this->x;
    }

    /**
     * @param float $x
     */
    public function setX ($x)
    {
        if ($x === null) {
            $this->x = null;
        }
        $this->x = (float)$x;
    }

    /**
     * @return float
     */
    public function getY ()
    {
        return $this->y;
    }

    /**
     * @param float $y
     */
    public function setY ($y)
    {
        if ($y === null) {
            $this->y = null;
        }
        $this->y = (float)$y;
    }

    /**
     * @return float
     */
    public function getLongitude ()
    {
        return $this->x;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude ($longitude)
    {
        $this->x = $longitude;
    }

    /**
     * @return float
     */
    public function getLatitude ()
    {
        return $this->y;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude ($latitude)
    {
        $this->y = $latitude;
    }
}