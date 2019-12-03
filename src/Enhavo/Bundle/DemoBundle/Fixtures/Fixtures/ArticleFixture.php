<?php

/**
 * ArticleFixture.php
 *
 * @since 27/07/16
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\DemoBundle\Fixtures\Fixtures;

use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Bundle\CommentBundle\Entity\Comment;
use Enhavo\Bundle\CommentBundle\Entity\Thread;
use Enhavo\Bundle\DemoBundle\Fixtures\AbstractFixture;
use Enhavo\Bundle\TaxonomyBundle\Entity\Taxonomy;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;

class ArticleFixture extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        $article = new Article();
        $article->setTitle($args['title']);
        $article->setTeaser($args['teaser']);
        $article->setPublic($args['public']);
        $article->setPublicationDate(new \DateTime());
        $article->setPicture($this->createImage($args['picture']));
        $article->setContent($this->createContent($args['content']));
        foreach($args['categories'] as $category) {
            $resource = $this->getCategory($category);
            if ($resource) {
                $article->addCategory($resource);
            }
        }

        $article->setThread($this->createThread());

        $this->translate($article);
        return $article;
    }

    function createThread()
    {
        $thread = new Thread();

        return $thread;
    }

    function getCategory($name): ?Term
    {
        $term = $this->manager->getRepository('EnhavoTaxonomyBundle:Term')->findOneBy([
            'name' => $name
        ]);

        if (!$term) {
            $term = $this->createTerm($name);
        }

        return $term;
    }

    function createTerm($name)
    {
        $category = new Term();
        $category->setName($name);
        $category->setSlug('/' . strtolower($name));
        $category->setTaxonomy($this->getTaxonomy('article_category'));

        return $category;
    }

    function getTaxonomy($name)
    {
        $taxonomy = $this->manager->getRepository('EnhavoTaxonomyBundle:Taxonomy')->findOneBy([
            'name' => $name
        ]);

        if (!$taxonomy) {
            $taxonomy = $this->createTaxonomy($name);
        }

        return $taxonomy;
    }

    function createTaxonomy($name)
    {
        $taxonomy = new Taxonomy();
        $taxonomy->setName($name);

        $this->manager->persist($taxonomy);

        return $taxonomy;
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'Article';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 20;
    }
}
