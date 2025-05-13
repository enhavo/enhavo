<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Behat\Exception;

class UserLoginException extends \Exception
{
    public static function invalidUser($username)
    {
        return new UserLoginException(sprintf('Can\'t login user "%s"', $username));
    }
}
