<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 14:31
 */

namespace Enhavo\Bundle\SearchBundle\Metadata\Provider;

use Enhavo\Component\Metadata\Exception\ProviderException;
use Enhavo\Component\Metadata\Metadata as BaseMetadata;
use Enhavo\Component\Metadata\ProviderInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;

class BundleProvider implements ProviderInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function provide(BaseMetadata $metadata, $normalizedData)
    {
        if(!$metadata instanceof Metadata) {
            throw ProviderException::invalidType($metadata, Metadata::class);
        }

        $metadata->setBundleName($this->getBundleName($metadata->getClassName()));
        $metadata->setHumanizedBundleName($this->getHumanizedBundleName($metadata->getClassName()));
        $metadata->setEntityName($this->getEntityName($metadata->getClassName()));
    }

    private function getBundleName($className)
    {
        $bundles = $this->kernel->getBundles();

        foreach($bundles as $bundle) {
            $class = get_class($bundle);
            $classParts = explode('\\', $class);
            $bundleName = array_pop($classParts);
            $bundlePath = implode('\\', $classParts);
            if(strpos($className, $bundlePath) === 0) {
                return $bundleName;
            }
        }
        return null;
    }

    private function getHumanizedBundleName($className)
    {
        $bundleName = $this->getBundleName($className);
        $splitClassName = preg_split('/([[:upper:]][[:lower:]]+)/', $bundleName, null, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
        return strtolower(implode('_', $splitClassName));
    }

    private function getEntityName($className)
    {
        $splitClassName = preg_split('/\\\\|\//', $className);
        $entityName = array_pop($splitClassName);
        return $entityName;
    }
}
