<?php

namespace Enhavo\Bundle\ContentBundle\Video;

use Enhavo\Bundle\ContentBundle\Model\Video;

interface ProviderInterface
{
    public function create(string $url): Video;

    public function isSupported(string $url): bool;
}
