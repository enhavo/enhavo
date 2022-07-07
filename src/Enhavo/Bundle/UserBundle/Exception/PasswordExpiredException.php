<?php
/**
 * @author blutze-media
 * @since 2022-07-07
 */

namespace Enhavo\Bundle\UserBundle\Exception;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class PasswordExpiredException extends CustomUserMessageAccountStatusException
{

}
