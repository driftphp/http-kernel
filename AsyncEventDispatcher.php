<?php

/*
 * This file is part of the Symfony Async Kernel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Drift\HttpKernel;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class AsyncEventDispatcher.
 */
class AsyncEventDispatcher extends EventDispatcher implements AsyncEventDispatcherInterface
{
    use AsyncEventDispatcherMethods;
}
