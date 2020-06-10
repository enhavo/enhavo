<?php
/**
 * MetadataCollector.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Component\Metadata\Driver;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Enhavo\Component\Metadata\DriverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class FileDriver implements DriverInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var string
     */
    private $configurationFile;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $configuration;

    public function __construct(KernelInterface $kernel, Filesystem $filesystem, $configurationFile)
    {
        $this->kernel = $kernel;
        $this->configurationFile = $configurationFile;
        $this->filesystem = $filesystem;
    }

    public function load()
    {
        // TODO: Implement load() method.
    }

    /**
     * @return array
     */
    public function getNormalizedData()
    {
        if($this->configuration !== null) {
            return $this->configuration;
        }

        $cacheFilePath = sprintf('%s/%s.json', $this->kernel->getCacheDir(), $this->configurationFile);
        $configuration = json_decode($this->filesystem->readFile($cacheFilePath), true);
        $this->configuration = $configuration;
        return $configuration;
    }
}
