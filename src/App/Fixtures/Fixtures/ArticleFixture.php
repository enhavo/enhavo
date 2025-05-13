<?php

namespace App\Fixtures\Fixtures;

use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Bundle\CommentBundle\Entity\Comment;
use Enhavo\Bundle\CommentBundle\Entity\Thread;
use App\Fixtures\AbstractFixture;
use Enhavo\Bundle\TaxonomyBundle\Entity\Taxonomy;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;

/**
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

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
        $thread = $this->container->get('enhavo_comment.factory.thread')->createNew();

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
        /** @var Term $term */
        $term = $this->container->get('enhavo_taxonomy.factory.term')->createNew();
        $term->setName($name);
        $term->setSlug('/' . strtolower($name));
        $term->setTaxonomy($this->getTaxonomy('article_category'));

        return $term;
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
        /** @var Taxonomy $taxonomy */
        $taxonomy = $this->container->get('enhavo_taxonomy.factory.taxonomy')->createNew();
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
