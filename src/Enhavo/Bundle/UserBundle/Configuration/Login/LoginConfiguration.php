<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Login;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\MaxFailedLoginAttemptsTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\PasswordMaxAgeTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\RedirectRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TemplateTrait;

class LoginConfiguration
{
    use TemplateTrait;
    use RedirectRouteTrait;
    use MaxFailedLoginAttemptsTrait;
    use PasswordMaxAgeTrait;

    private ?string $route = null;

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): void
    {
        $this->route = $route;
    }
}
