<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 30.10.18
 * Time: 18:24
 */

namespace Enhavo\Bundle\ShopBundle\Manager;

use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Enhavo\Bundle\ShopBundle\Entity\ProductOption;

class ProductVariantManager
{
    public function generateCode($name, $options): string
    {
        $optionString = [];
        /** @var ProductOption $option */
        foreach ($options as $option) {
            $optionString[] = $option->getCode();
        }

        return substr(md5(microtime()),rand(0,26),4) . '-' . Slugifier::slugify($name) . '-' . implode('-', $optionString);
    }
}
