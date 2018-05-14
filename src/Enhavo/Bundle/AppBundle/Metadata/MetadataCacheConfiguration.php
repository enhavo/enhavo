<?php
/**
 * MetadataCollector.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Metadata;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class MetadataCacheConfiguration implements MetadataConfigurationInterface
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

    /**
     * @return array
     */
    public function getConfiguration()
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