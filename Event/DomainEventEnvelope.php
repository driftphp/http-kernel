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

namespace Drift\HttpKernel\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class DomainEventEnvelope.
 */
final class DomainEventEnvelope extends Event
{
    private $domainEvent;

    /**
     * DomainEventEnvelope constructor.
     *
     * @param object $domainEvent
     */
    public function __construct(object $domainEvent)
    {
        $this->domainEvent = $domainEvent;
    }

    /**
     * Get domain event.
     *
     * @return object
     */
    public function getDomainEvent(): object
    {
        return $this->domainEvent;
    }
}
