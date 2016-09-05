<?php
/**
 * MetadataCollector.php
 *
 * @since 23/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SearchBundle\Metadata;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Parser;

class MetadataCollector
{
    const CACHE_FILE_NAME = 'search_metadata_array.json';

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * MetadataCollector constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel, Filesystem $filesystem)
    {
        $this->kernel = $kernel;
        $this->filesystem = $filesystem;
    }

    /**
     * @return array
     */
    public function getConfigurations()
    {
        if($this->configuration !== null) {
            return $this->configuration;
        }

        $cacheFilePath = sprintf('%s/%s', $this->kernel->getCacheDir(), self::CACHE_FILE_NAME);
        $configuration = json_decode($this->filesystem->readFile($cacheFilePath), true);
        $this->configuration = $configuration;
        return $configuration;
    }
}