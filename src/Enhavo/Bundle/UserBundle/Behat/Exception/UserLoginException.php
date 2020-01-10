<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-10
 * Time: 15:48
 */

namespace Enhavo\Bundle\UserBundle\Behat\Exception;


class UserLoginException extends \HttpInvalidParamException
{
    public static function invalidUser($username)
    {
        return new  UserLoginException(sprintf('Can\'t login user "%s"', $username));
    }
}
