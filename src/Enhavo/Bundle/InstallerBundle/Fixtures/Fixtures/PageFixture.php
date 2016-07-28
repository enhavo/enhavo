<?php

/**
 * PageFixture.php
 *
 * @since 27/07/16
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\InstallerBundle\Fixtures\Fixtures;

use Enhavo\Bundle\InstallerBundle\Fixtures\AbstractFixture;
use Enhavo\Bundle\PageBundle\Entity\Page;

class PageFixture extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        $page = new Page();
        $page->setTitle($args['title']);
        $page->setPublic($args['public']);
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
