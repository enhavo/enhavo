<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 30.10.18
 * Time: 18:24
 */

namespace Enhavo\Bundle\ShopBundle\Manager;

use Enhavo\Bundle\AppBundle\Util\NameTransformer;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;

class ProductManager
{
    public function generateCode($name)
    {
        return substr(md5(microtime()),rand(0,26),4) . '-' . Slugifier::slugify($name);
    }
}
