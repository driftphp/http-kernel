<?php

/*
 * This file is part of the DriftPHP Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Drift\HttpKernel\Tests\Services;

/**
 * Class ACounter.
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
     * Increase.
     */
    public function increaseI()
    {
        ++$this->i;
    }

    /**
     * Increase.
     */
    public function increaseX()
    {
        ++$this->x;
    }

    /**
     * Increase.
     */
    public function increase2X()
    {
        ++$this->x;
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
