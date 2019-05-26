<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-24
 * Time: 18:08
 */

namespace Enhavo\Bundle\AppBundle\Init;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface InitInterface extends TypeInterface
{
    public function init(Output $io);
}
