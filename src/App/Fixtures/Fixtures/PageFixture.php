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
use Enhavo\Bundle\PageBundle\Entity\Page;

/**
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */
class PageFixture extends AbstractFixture
{
    public function create($args)
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

    public function getName()
    {
        return 'Page';
    }

    public function getOrder()
    {
        return 20;
    }
}
