<?php

namespace Enhavo\Bundle\ContentBundle\Tests\Video;

use Enhavo\Bundle\ContentBundle\Video\VimeoProvider;
use PHPUnit\Framework\TestCase;

class VimeoProviderTest extends TestCase
{
    public function testIsSupported()
    {
        $provider = new VimeoProvider();

        $this->assertTrue($provider->isSupported('https://vimeo.com/91871495'));
        $this->assertFalse($provider->isSupported('vimeo'));
    }
}
