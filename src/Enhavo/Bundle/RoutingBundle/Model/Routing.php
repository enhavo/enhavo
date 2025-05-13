<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Model;

interface Routing
{
    public const STRATEGY_ROUTE = 'route';

    public const STRATEGY_SLUG = 'slug';

    public const STRATEGY_SLUG_ID = 'slug_id';

    public const STRATEGY_ID = 'id';
}
