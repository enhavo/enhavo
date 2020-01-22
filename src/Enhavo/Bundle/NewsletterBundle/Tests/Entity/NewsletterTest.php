<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 19:20
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
