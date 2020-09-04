<?php


namespace Drift\HttpKernel\Tests\Services;

/**
 * Class ACounter
 */
class ACounter
{
    /**
     * @var int
     */
    private $i = 0;

    /**
     * @var int
     */
    private $x = 0;

    /**
     * Increase
     */
    public function increaseI()
    {
        $this->i++;
    }

    /**
     * Increase
     */
    public function increaseX()
    {
        $this->x++;
    }

    /**
     * Increase
     */
    public function increase2X()
    {
        $this->x++;
    }

    /**
     * @return int
     */
    public function getI()
    {
        return $this->i;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }
}