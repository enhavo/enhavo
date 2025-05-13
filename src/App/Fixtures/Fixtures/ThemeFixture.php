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

/**
 * @author gseidel
 */
class ThemeFixture extends AbstractFixture
{
    public function create($args)
    {
        $theme = $this->container->get('enhavo_theme.factory.theme')->createNew();
        $theme->setKey($args['key']);
        $theme->setActive($args['active']);

        return $theme;
    }

    public function getName()
    {
        return 'Theme';
    }

    public function getOrder()
    {
        return 2;
    }
}
