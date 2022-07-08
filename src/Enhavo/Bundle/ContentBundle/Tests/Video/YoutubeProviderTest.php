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
        $provider = new YoutubeProvider(null, YoutubeProvider::IMAGE_TYPE_MAX, false);

        $video = $provider->create('https://www.youtube.com/watch?v=SomeKey');

<<<<<<< HEAD
        $this->assertEquals('http://www.youtube.com/embed/SomeKey', $video->getEmbedUrl());
        $this->assertEquals('http://www.youtube.com/watch?v=SomeKey', $video->getVideoUrl());
        $this->assertEquals('https://i.ytimg.com/vi/SomeKey/maxresdefault.jpg', $video->getThumbnail());
=======
        $this->assertEquals('https://www.youtube.com/embed/S0meK3y', $video->getEmbedUrl());
        $this->assertEquals('https://www.youtube.com/watch?v=S0meK3y', $video->getVideoUrl());
        $this->assertEquals('https://i.ytimg.com/vi/S0meK3y/maxresdefault.jpg', $video->getThumbnail());
        $this->assertEquals(YoutubeProvider::PROVIDER_NAME, $video->getProvider());
    }

    public function testCreateFromShortUrlWithoutApiKey()
    {
        $provider = new YoutubeProvider(null, YoutubeProvider::IMAGE_TYPE_MAX, false);

        $video = $provider->create('https://youtu.be/S0meK3y');

        $this->assertEquals('https://www.youtube.com/embed/S0meK3y', $video->getEmbedUrl());
        $this->assertEquals('https://www.youtube.com/watch?v=S0meK3y', $video->getVideoUrl());
        $this->assertEquals('https://i.ytimg.com/vi/S0meK3y/maxresdefault.jpg', $video->getThumbnail());
>>>>>>> 7a83e2be4 (Check multiple urls for youtube videos (#1608))
        $this->assertEquals(YoutubeProvider::PROVIDER_NAME, $video->getProvider());
    }
}
