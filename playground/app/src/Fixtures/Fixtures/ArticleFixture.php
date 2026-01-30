<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixtures\Fixtures;

use App\Fixtures\AbstractFixture;
use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Bundle\TaxonomyBundle\Entity\Taxonomy;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;

/**
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */
class ArticleFixture extends AbstractFixture
{
    public function create($args)
    {
        $article = new Article();
        $article->setTitle($args['title']);
        $article->setTeaser($args['teaser']);
        $article->setPublic($args['public']);
        $article->setPublicationDate(new \DateTime());
        $article->setPicture($this->createImage($args['picture']));
        $article->setContent($this->createContent($args['content']));
        foreach ($args['categories'] as $category) {
            $resource = $this->getCategory($category);
            if ($resource) {
                $article->addCategory($resource);
            }
        }

        $article->setThread($this->createThread());

        $this->translate($article);

        return $article;
    }

    public function createThread()
    {
        $thread = $this->container->get('enhavo_comment.factory.thread')->createNew();

        return $thread;
    }

    public function getCategory($name): ?Term
    {
        $term = $this->manager->getRepository('EnhavoTaxonomyBundle:Term')->findOneBy([
            'name' => $name,
        ]);

        if (!$term) {
            $term = $this->createTerm($name);
        }

        return $term;
    }

    public function createTerm($name)
    {
        /** @var Term $term */
        $term = $this->container->get('enhavo_taxonomy.factory.term')->createNew();
        $term->setName($name);
        $term->setSlug('/'.strtolower($name));
        $term->setTaxonomy($this->getTaxonomy('article_category'));

        return $term;
    }

    public function getTaxonomy($name)
    {
        $taxonomy = $this->manager->getRepository('EnhavoTaxonomyBundle:Taxonomy')->findOneBy([
            'name' => $name,
        ]);

        if (!$taxonomy) {
            $taxonomy = $this->createTaxonomy($name);
        }

        return $taxonomy;
    }

    public function createTaxonomy($name)
    {
        /** @var Taxonomy $taxonomy */
        $taxonomy = $this->container->get('enhavo_taxonomy.factory.taxonomy')->createNew();
        $taxonomy->setName($name);

        $this->manager->persist($taxonomy);

        return $taxonomy;
    }

    public function getName()
    {
        return 'Article';
    }

    public function getOrder()
    {
        return 20;
    }
}
