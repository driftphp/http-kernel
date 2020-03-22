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

use Drift\HttpKernel\DependencyInjection\CompilerPass\AsyncServicesCompilerPass;
use Drift\HttpKernel\DependencyInjection\CompilerPass\EventDispatcherCompilerPass;
use Drift\HttpKernel\DependencyInjection\CompilerPass\EventLoopCompilerPass;
use Drift\HttpKernel\DependencyInjection\CompilerPass\FilesystemCompilerPass;
use Drift\HttpKernel\Exception\AsyncHttpKernelNeededException;
use Exception;
use function React\Promise\reject;
use React\Promise\PromiseInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AsyncKernel.
 */
abstract class AsyncKernel extends Kernel implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $uid;

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if (!$this->booted) {
            $this->uid = $this->generateUID();
        }

        parent::boot();
    }

    /**
     * Preload kernel.
     */
    public function preload(): PromiseInterface
    {
        return $this
            ->getHttpKernel()
            ->preload();
    }

    /**
     * Handles a Request to convert it to a Response.
     *
     * When $catch is true, the implementation must catch all exceptions
     * and do its best to convert them to a Response instance.
     */
    public function handleAsync(Request $request): PromiseInterface
    {
        $httpKernel = $this->getHttpKernel();
        if (!$httpKernel instanceof AsyncHttpKernel) {
            reject(
                new AsyncHttpKernelNeededException('In order to use this AsyncKernel, you need to have the HttpAsyncKernel installed')
            );
        }

        return $httpKernel->handleAsync($request);
    }

    /**
     * Build the kernel.
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new EventLoopCompilerPass());
        $container->addCompilerPass(new EventDispatcherCompilerPass($this->isDebug()));
        $container->addCompilerPass(new FilesystemCompilerPass());
        $container->addCompilerPass(new AsyncServicesCompilerPass());
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has('http_kernel')) {
            $container
                ->getDefinition('http_kernel')
                ->setClass(AsyncHttpKernel::class);
        }
    }

    /**
     * Get uid.
     *
     * @return string
     */
    public function getUID(): string
    {
        if (!$this->booted) {
            throw new Exception('You cannot check the UID of a non-booted-yet kernel');
        }

        return $this->uid;
    }

    /**
     * @return string
     */
    private function generateUID()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < 7; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
