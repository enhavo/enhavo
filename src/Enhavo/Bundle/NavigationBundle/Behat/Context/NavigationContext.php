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

use Enhavo\Bundle\AppBundle\Behat\Context\KernelContext;
use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\PageBundle\Entity\Page;
use PHPUnit\Framework\Assert;

class NavigationContext extends KernelContext
{
    /**
     * @var Page
     */
    public static $page;

    /**
     * @var Node
     */
    public static $node;

    /**
     * @Given node navigation object
     */
    public function nodeNavigationObject()
    {
        self::$page = new Page();
        $this->getManager()->persist(self::$page);
    }

    /**
     * @Given page navigation object
     */
    public function pageNavigationObject()
    {
        self::$node = new Node();
        $this->getManager()->persist(self::$node);
    }

    /**
     * @Given node navigation has type :type
     */
    public function nodeNavigationHasType($type)
    {
        self::$node->setType($type);
    }

    /**
     * @Then I add page navigation object to node navigation object
     */
    public function iAddPageNavigationObjectToNodeNavigationObject()
    {
        self::$node->setContent(self::$page);
    }

    /**
     * @Then node navigation object content class should be :class
     */
    public function nodeNavigationObjectContentClassShouldBe($class)
    {
        Assert::assertEquals($class, self::$node->getContentClass());
    }

    /**
     * @Then node navigation object content id should be same as page navigation object
     */
    public function nodeNavigationObjectContentIdShouldBeSameAsPageNavigationObject()
    {
        Assert::assertEquals(self::$page->getId(), self::$node->getContentId());
    }
}
