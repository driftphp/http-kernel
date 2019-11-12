<?php


namespace Drift\HttpKernel\Tests;

use Drift\HttpKernel\AsyncKernel;
use Mmoreram\BaseBundle\Kernel\BaseKernelTrait;

/**
 * Class AsyncTestKernel
 */
final class AsyncTestKernel extends AsyncKernel
{
    use BaseKernelTrait;

    /**
     * Gets the name of the kernel.
     *
     * @return string The kernel name
     *
     * @deprecated since Symfony 4.2
     */
    public function getName()
    {
        return 'drift_test';
    }

    /**
     * Gets the application root dir (path of the project's composer file).
     *
     * @return string The project root dir
     */
    public function getProjectDir()
    {
        return $this->getRootDir();
    }
}