<?php

/**
 * ArticleTest.php
 *
 * @since 15/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ArticleBundle\Tests\Entity;

use Enhavo\Bundle\ArticleBundle\Entity\Article;

class ArticleTest extends \PHPUnit_Framework_TestCase
{
    public function testCreated()
    {
        $article = new Article();
        $date = new \DateTime('1970-01-01 00:00:00');
        $article->setCreated($date);
        $this->assertEquals('1970-01-01 00:00:00', $article->getCreated()->format('Y-m-d H:i:s'));
    }
}