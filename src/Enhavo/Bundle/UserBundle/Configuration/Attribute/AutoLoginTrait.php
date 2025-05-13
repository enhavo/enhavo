<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait AutoLoginTrait
{
    private ?bool $autoLogin = null;

    public function isAutoLogin(): ?bool
    {
        return $this->autoLogin;
    }

    public function setAutoLogin(?bool $autoLogin): void
    {
        $this->autoLogin = $autoLogin;
    }
}
