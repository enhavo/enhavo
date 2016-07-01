<?php
/**
 * MetadataCollector.php
 *
 * @since 23/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SearchBundle\Metadata;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Parser;

class MetadataCollector
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * MetadataCollector constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel, Finder $finder)
    {
        $this->kernel = $kernel;
        $this->finder = $finder;
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
        $finder = $this->finder;

        if(file_get_contents($file)){
            $contents = file_get_contents($file);
            $data = $parser->parse($contents);
            return $data;
        }
        /*foreach ($finder->files()->in($file) as $file) {
            $contents = $file->getContents();
            $data = $parser->parse($contents);
            return $data;
        }*/

        return [];
    }
}