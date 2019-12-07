<?php

/*
 * This file is part of the Drift Http Kernel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Drift\HttpKernel\DependencyInjection\CompilerPass;

use React\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class FilesystemCompilerPass
 */
class FilesystemCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        /*
         * Create a filesystem instance
         */
        if (!$container->has('drift.filesystem')) {
            $filesystem = new Definition(Filesystem::class, [
                new Reference('drift.event_loop'),
            ]);

            $filesystem->setFactory([
                Filesystem::class,
                'create',
            ]);

            $filesystem->setPublic(true);
            $container->setDefinition('drift.filesystem', $filesystem);
            $container->setAlias(Filesystem::class, 'drift.filesystem');
        }
    }
}