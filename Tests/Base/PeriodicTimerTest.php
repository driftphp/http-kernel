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
        $configuration['parameters']['timer_freq_03'] = 0.3;
        $configuration['parameters']['timer_freq_07'] = 0.7;
        $configuration['parameters']['increase_2x'] = 'increase2x';
        $configuration['services'][ACounter::class] = [
            'class' => ACounter::class,
            'tags' => [
                [
                    'name' => 'periodic_timer',
                    'interval' => 0.1,
                    'method' => 'increaseI',
                ],
                [
                    'name' => 'periodic_timer',
                    'interval' => 0.1,
                    'method' => 'increaseX',
                ],
                [
                    'name' => 'periodic_timer',
                    'interval' => '%timer_freq_03%',
                    'method' => '%increase_2x%',
                ],
                [
                    'name' => 'periodic_timer',
                    'interval' => '%timer_freq_07%',
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
