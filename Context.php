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

namespace Drift\HttpKernel;

/**
 * Class Context.
 */
class Context
{
    /**
     * Values.
     */
    private $values;

    /**
     * Add context value.
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function set(
        string $key,
        string $value
    ): void {
        $this->values[$key] = $value;
    }

    /**
     * Delete context key.
     *
     * @param string $key
     *
     * @return void
     */
    public function delete(string $key): void
    {
        unset($this->values[$key]);
    }

    /**
     * Get context value.
     *
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ? string
    {
        return $this->values[$key] ?? null;
    }

    /**
     * Clear context.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->values = [];
    }

    /**
     * Get all values.
     *
     * @return string[]
     */
    public function all(): array
    {
        return $this->values;
    }
}
