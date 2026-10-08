<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Factory;

use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\UserBundle\Model\TwoFactorInterface;

/**
 * @author blutze-media
 */
class UserFactory extends Factory
{
    private bool $twoFactorDefaultEnabled = false;
    private ?string $twoFactorDefaultMethod = null;

    public function setTwoFactorConfig(bool $defaultEnabled, ?string $defaultMethod): void
    {
        $this->twoFactorDefaultEnabled = $defaultEnabled;
        $this->twoFactorDefaultMethod = $defaultMethod;
    }

    public function createNew()
    {
        $user = parent::createNew();

        if ($this->twoFactorDefaultEnabled && $this->twoFactorDefaultMethod && $user instanceof TwoFactorInterface) {
            $user->setTwoFactorMethod($this->twoFactorDefaultMethod);
        }

        return $user;
    }
}
