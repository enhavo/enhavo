<?php
/**
 * Routing.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Routing;


interface Routing
{
    const STRATEGY_ROUTE = 'route';

    const STRATEGY_SLUG = 'slug';

    const STRATEGY_SLUG_ID = 'slug_id';

    const STRATEGY_ID = 'id';
}