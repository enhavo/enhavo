<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Slugifier;

use PHPUnit\Framework\TestCase;

class SlugifierTest extends TestCase
{
    public function testSlugify()
    {
        $slugifier = new Slugifier();
        $this->assertEquals('this-is-a-test-string', $slugifier->slugify('This is a test string'));
        $this->assertEquals('uebel-weiss', $slugifier->slugify('Übel weiß'));
        $this->assertEquals('no-need', Slugifier::slugify('no$need'));
    }
}
