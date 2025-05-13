<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Model;

class Video
{
    public function __construct(
        private string $provider,
        private string $title,
        private string $description,
        private string $thumbnail,
        private string $videoUrl,
        private string $embedUrl,
    ) {
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function getVideoUrl(): string
    {
        return $this->videoUrl;
    }

    public function getEmbedUrl(): string
    {
        return $this->embedUrl;
    }
}
