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

        $bundles = $this->kernel->getBundles();

        $configuration = [];

        foreach($bundles as $bundle) {
            try {
                $class = get_class($bundle);
                $classParts = explode('\\', $class);
                $bundleName = array_pop($classParts);
                $file = $this->kernel->locateResource(sprintf('@%s/Resources/config/search.yml', $bundleName));
            } catch(\Exception $e) {
                continue;
            }

            $data = $this->parseFile($file);

            if(is_array($data)) {
                foreach($data as $class => $config) {
                    $configuration[$class] = $config;
                }
            }
        }

        $this->configuration = $configuration;
        return $configuration;
    }

    /**
     * @param $file
     * @return array
     */
    protected function parseFile($file)
    {
        $parser = new Parser();
        $contents = $this->filesystem->readFile($file);
        $data = $parser->parse($contents);
        return $data;
    }
}