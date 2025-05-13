<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
