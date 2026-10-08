<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Model;

interface TwoFactorInterface
{
    public function getTwoFactorMethod(): ?string;

    public function setTwoFactorMethod(?string $method): void;

    public function getTwoFactorRecoveryCode(): ?string;

    public function setTwoFactorRecoveryCode(?string $code): void;

    public function getTwoFactorSecretData(): mixed;

    public function setTwoFactorSecretData(mixed $data): void;
}
