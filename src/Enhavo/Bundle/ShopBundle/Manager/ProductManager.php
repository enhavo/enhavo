<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 30.10.18
 * Time: 18:24
 */

namespace Enhavo\Bundle\ShopBundle\Manager;

use Enhavo\Bundle\AppBundle\Util\NameTransformer;

class ProductManager
{
    /** @var NameTransformer */
    private $nameTransformer;

    /**
     * ProductManager constructor.
     */
    public function __construct()
    {
        $this->nameTransformer = new NameTransformer();
    }

    public function generateCode($name)
    {
        return $this->nameTransformer->kebabCase($name);
    }
}
