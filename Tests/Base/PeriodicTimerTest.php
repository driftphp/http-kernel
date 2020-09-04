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

namespace Drift\HttpKernel\Tests\Base;

use function Clue\React\Block\await;
use Drift\HttpKernel\Tests\AsyncKernelFunctionalTest;
use Drift\HttpKernel\Tests\Services\ACounter;
use function Drift\React\usleep;

/**
 * Class PeriodicTimerTest.
 */
class PeriodicTimerTest extends AsyncKernelFunctionalTest
{
    /**
     * Decorate configuration.
     *
     * @param array $configuration
     *
     * @return array
     */
    protected static function decorateConfiguration(array $configuration): array
    {
        $configuration = parent::decorateConfiguration($configuration);
        $configuration['services'][ACounter::class] = [
            'class' => ACounter::class,
            'tags' => [
                [
                    'name' => 'periodic_timer',
                    'interval' => '0.1',
                    'method' => 'increaseI',
                ],
                [
                    'name' => 'periodic_timer',
                    'interval' => '0.1',
                    'method' => 'increaseX',
                ],
                [
                    'name' => 'periodic_timer',
                    'interval' => '0.3',
                    'method' => 'increase2X',
                ],
                [
                    'name' => 'periodic_timer',
                    'interval' => '0.7',
                    'method' => 'increaseX',
                ],
            ],
        ];

        return $configuration;
    }

    /**
     * Test periodic timer.
     */
    public function testPeriodicTimer()
    {
        self::$kernel->preload();

        $loop = static::get('reactphp.event_loop');
        await(usleep(1050000, $loop), $loop);

        $this->assertEquals(10, self::get(ACounter::class)->getI());
        $this->assertEquals(14, self::get(ACounter::class)->getX());

        await(usleep(350000, $loop), $loop);

        $this->assertEquals(13, self::get(ACounter::class)->getI());
        $this->assertEquals(19, self::get(ACounter::class)->getX());
    }
}
