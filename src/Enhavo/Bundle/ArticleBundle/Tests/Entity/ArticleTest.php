<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ArticleBundle\Entity;

use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    public function testCreated()
    {
        $article = new Article();
        $date = new \DateTime('1970-01-01 00:00:00');
        $article->setCreatedAt($date);
        $this->assertEquals('1970-01-01 00:00:00', $article->getCreatedAt()->format('Y-m-d H:i:s'));
    }
}
