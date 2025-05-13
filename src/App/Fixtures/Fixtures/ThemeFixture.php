<?php

namespace App\Fixtures\Fixtures;

use App\Fixtures\AbstractFixture;
use Enhavo\Bundle\UserBundle\Model\User;

/**
 * @author gseidel
 */
class ThemeFixture extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        $theme = $this->container->get('enhavo_theme.factory.theme')->createNew();
        $theme->setKey($args['key']);
        $theme->setActive($args['active']);

        return $theme;
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'Theme';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 2;
    }
}
