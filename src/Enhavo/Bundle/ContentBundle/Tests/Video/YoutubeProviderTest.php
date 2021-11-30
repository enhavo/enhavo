<?php

namespace Enhavo\Bundle\ContentBundle\Tests\Video;

use Enhavo\Bundle\ContentBundle\Video\YoutubeProvider;
use PHPUnit\Framework\TestCase;

class YoutubeProviderTest extends TestCase
{
    public function testIsSupported()
    {
        $provider = new YoutubeProvider();

        $this->assertTrue($provider->isSupported('https://www.youtube.com/watch?v=SomeKey'));
        $this->assertFalse($provider->isSupported('youtube'));
    }

    public function testCreateWithoutApiKey()
    {
        $provider = new YoutubeProvider();

        $video = $provider->create('https://www.youtube.com/watch?v=SomeKey');

        $this->assertEquals('http://www.youtube.com/embed/SomeKey', $video->getEmbedUrl());
        $this->assertEquals('http://www.youtube.com/watch?v=SomeKey', $video->getVideoUrl());
        $this->assertEquals('https://i.ytimg.com/vi/SomeKey/maxresdefault.jpg', $video->getThumbnail());
        $this->assertEquals(YoutubeProvider::PROVIDER_NAME, $video->getProvider());
    }
}
