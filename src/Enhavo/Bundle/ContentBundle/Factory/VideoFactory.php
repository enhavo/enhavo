<?php

namespace Enhavo\Bundle\ContentBundle\Factory;

use Enhavo\Bundle\ContentBundle\Exception\VideoException;
use Enhavo\Bundle\ContentBundle\Model\Video;
use Enhavo\Bundle\ContentBundle\Video\ProviderInterface;

class VideoFactory
{
    /** @var ProviderInterface[] */
    private $providers;

    /**
     * @param ProviderInterface $provider
     */
    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * @throws VideoException
     */
    public function create($url): Video
    {
        if ($url) {
            foreach ($this->providers as $provider) {
                if ($provider->isSupported($url)) {
                    return $provider->create($url);
                }
            }
        }

        throw VideoException::noProviderFound($url);
    }

    public function isSupported($url): bool
    {
        foreach ($this->providers as $provider) {
            if ($provider->isSupported($url)) {
                return true;
            }
        }
        return false;
    }
}
