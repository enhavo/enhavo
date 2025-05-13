<?php

namespace Enhavo\Bundle\UserBundle\Exception;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

/**
 * @author blutze-media
 */
class VerificationRequiredException extends CustomUserMessageAccountStatusException
{

}
