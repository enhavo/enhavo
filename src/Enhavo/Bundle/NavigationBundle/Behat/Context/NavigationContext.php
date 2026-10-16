<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Behat\Context;

use Behat\Step\Given;
use Behat\Step\Then;
use Enhavo\Bundle\AppBundle\Behat\Context\KernelAwareContext;
use Enhavo\Bundle\AppBundle\Behat\Context\ManagerAwareTrait;
use Enhavo\Bundle\NavigationBundle\Entity\Content;
use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\PageBundle\Entity\Page;
use PHPUnit\Framework\Assert;

class NavigationContext implements KernelAwareContext
{
    use ManagerAwareTrait;

    private ?Page $page = null;
    private ?Node $node = null;

    #[Given('node navigation object')]
    public function nodeNavigationObject()
    {
        $this->node = new Node();
        $this->getManager()->persist($this->node);
    }

    #[Given('page navigation object')]
    public function pageNavigationObject()
    {
        $this->page = new Page();
        $this->getManager()->persist($this->page);
    }

    #[Then('I add page navigation object to node navigation object')]
    public function iAddPageNavigationObjectToNodeNavigationObject()
    {
        $content = new Content();
        $content->setContent($this->page);
        $this->node->setSubject($content);
    }

    #[Then('node navigation object content class should be :class')]
    public function nodeNavigationObjectContentClassShouldBe($class)
    {
        Assert::assertEquals($class, $this->getContent()->getContentClass());
    }

    #[Then('node navigation object content id should be same as page navigation object')]
    public function nodeNavigationObjectContentIdShouldBeSameAsPageNavigationObject()
    {
        Assert::assertEquals($this->page->getId(), $this->getContent()->getContentId());
    }

    private function getContent(): Content
    {
        $content = $this->node->getSubject();
        Assert::assertInstanceOf(Content::class, $content);

        return $content;
    }
}
