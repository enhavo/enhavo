<?php

namespace Enhavo\Bundle\DemoBundle\Fixtures\Fixtures;

use Enhavo\Bundle\DemoBundle\Fixtures\AbstractFixture;
use Enhavo\Bundle\SidebarBundle\Entity\Sidebar;

class SidebarFixture extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        /** @var Sidebar $sidebar */
        $sidebar = $this->container->get('enhavo_sidebar.factory.sidebar')->createNew();
        $sidebar->setName($args['name']);
        $sidebar->setCode($args['code']);
        $sidebar->setContent($this->createContent($args['content']));

        $this->translate($sidebar);
        return $sidebar;
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'Sidebar';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 10;
    }
}
