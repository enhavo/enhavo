<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Tests\Entity;

use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use PHPUnit\Framework\TestCase;

class NewsletterTest extends TestCase
{
    public function testStates()
    {
        $newsletter = new Newsletter();

        $this->assertEquals(Newsletter::STATE_CREATED, $newsletter->getState(), 'State should be created as default');
        $this->assertFalse($newsletter->isSent(), 'State should be not send as default');

        $newsletter->setState(Newsletter::STATE_SENT);
        $this->assertTrue($newsletter->isSent(), 'isSent should be true if state was set to sent');
    }
}
