<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 20:52
 */

namespace Enhavo\Bundle\MediaBundle\Provider;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

class ChainProvider implements ProviderInterface
{
    /**
     * @var ProviderInterface[]
     */
    private $providers;

    /**
     * @param ProviderInterface $provider
     */
    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * @param ProviderInterface $provider
     */
    public function removeProvider(ProviderInterface $provider)
    {
        if (false !== $key = array_search($provider, $this->providers, true)) {
            array_splice($this->providers, $key, 1);
        }
    }

    public function updateFile(FileInterface $file)
    {
        foreach($this->providers as $provider) {
            $provider->updateFile($file);
        }
    }

    public function updateFormat(FormatInterface $format)
    {
        foreach($this->providers as $provider) {
            $provider->updateFormat($format);
        }
    }

    public function supportsClass($object)
    {
        foreach($this->providers as $provider) {
            if($provider->supportsClass($object)) {
                return true;
            }
        }
        return false;
    }
}