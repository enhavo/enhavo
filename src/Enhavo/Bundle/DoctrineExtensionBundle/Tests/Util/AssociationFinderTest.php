<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\Util;

use Enhavo\Bundle\DoctrineExtensionBundle\Tests\DoctrineTest;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\AssociationFinder\Article;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\AssociationFinder\File;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\AssociationFinder\Page;
use Enhavo\Bundle\DoctrineExtensionBundle\Util\AssociationFinder;

class AssociationFinderTest extends DoctrineTest
{
    protected $entityDir = __DIR__.'/../Fixtures/Entity/AssociationFinder';
    protected $proxyDir = __DIR__.'/../Fixtures/Proxy';

    public function testReferenceTo()
    {
        $finder = new AssociationFinder($this->em);

        $file = new File();
        $page = new Page();
        $article = new Article();

        $page->setFile($file);
        $article->setFile($file);

        $this->em->persist($file);
        $this->em->persist($page);
        $this->em->persist($article);

        $this->em->flush();

        $references = $finder->findAssociationsTo($file);

        $this->assertCount(2, $references);
        $this->assertTrue(in_array($page, $references));
        $this->assertTrue(in_array($article, $references));
    }
}
