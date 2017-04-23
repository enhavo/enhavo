<?php

/**
 * CategoryFixture.php
 *
 * @since 27/07/16
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\InstallerBundle\Fixtures\Fixtures;

use Enhavo\Bundle\CategoryBundle\Entity\Category;
use Enhavo\Bundle\CategoryBundle\Entity\Collection;
use Enhavo\Bundle\InstallerBundle\Fixtures\AbstractFixture;

class CategoryFixture extends AbstractFixture
{
    /**
     * @var Collection[]
     */
    private $collections = [];

    /**
     * @inheritdoc
     */
    function create($args)
    {
        $category = new Category();
        $category->setName($args['name']);
        $category->setCollection($this->getCollection($args['collection']));
        $category->setPicture($this->createImage($args['picture']));
        $this->translate($category);
        return $category;
    }

    function getCollection($name)
    {
        $collection = $this->manager->getRepository('EnhavoCategoryBundle:Collection')->findOneBy([
            'name' => $name
        ]);

        if(empty($collection)) {
            foreach($this->collections as $collection) {
                if($collection->getName() == $name) {
                    return $collection;
                }
            }

            $collection = new Collection();
            $collection->setName($name);
            $this->manager->persist($collection);
            $this->collections[] = $collection;
        }

        return $collection;
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'Category';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 10;
    }
}
