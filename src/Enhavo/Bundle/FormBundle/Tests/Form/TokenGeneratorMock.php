<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-24
 * Time: 20:11
 */

namespace Enhavo\Bundle\FormBundle\Tests\Form;

use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;

class TokenGeneratorMock implements TokenGeneratorInterface
{
    public function generateToken($length = 32)
    {
        return substr(md5(uniqid()), 0, $length);
    }
}
