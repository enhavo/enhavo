<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Factory;

use Enhavo\Bundle\ContentBundle\Exception\VideoException;
use Enhavo\Bundle\ContentBundle\Model\Video;
use Enhavo\Bundle\ContentBundle\Video\ProviderInterface;

class VideoFactory
{
    /** @var ProviderInterface[] */
    private $providers;

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
