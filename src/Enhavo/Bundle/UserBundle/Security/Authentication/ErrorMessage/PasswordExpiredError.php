<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Security\Authentication\ErrorMessage;

use Enhavo\Bundle\UserBundle\Exception\PasswordExpiredException;
use Enhavo\Bundle\UserBundle\Security\Authentication\ErrorMessageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordExpiredError implements ErrorMessageInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    public function supports($error): bool
    {
        return $error instanceof PasswordExpiredException;
    }

    public function getMessage(): string
    {
        return $this->translator->trans('login.error.password_expired', [], 'EnhavoUserBundle');
    }
}
