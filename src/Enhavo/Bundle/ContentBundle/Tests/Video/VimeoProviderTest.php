<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
