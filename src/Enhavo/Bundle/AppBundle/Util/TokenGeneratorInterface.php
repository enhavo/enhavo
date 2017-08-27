<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.08.17
 * Time: 12:27
 */

namespace Enhavo\Bundle\AppBundle\Util;


interface TokenGeneratorInterface
{
    public function generateToken($length = 32);
}