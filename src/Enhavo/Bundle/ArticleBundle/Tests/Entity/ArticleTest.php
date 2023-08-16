<?php

/**
 * ArticleTest.php
 *
 * @since 15/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ArticleBundle\Entity;

use Enhavo\Bundle\ArticleBundle\Entity\Article;
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
