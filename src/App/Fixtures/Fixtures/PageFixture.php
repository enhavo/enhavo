<?php

namespace App\Fixtures\Fixtures;

use App\Fixtures\AbstractFixture;
use Enhavo\Bundle\PageBundle\Entity\Page;

/**
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */
class PageFixture extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        /** @var Page $page */
        $page = $this->container->get('enhavo_page.factory.page')->createNew();
        $page->setTitle($args['title']);
        $page->setPublic($args['public']);
        $page->setSlug($args['slug']);
        $page->setContent($this->createContent($args['content']));
        $page->setRoute($this->createRoute($args['route'], $page));
        $this->translate($page);
        return $page;
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'Page';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 20;
    }
}
