<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Login;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\RedirectRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TemplateTrait;

class LoginConfiguration
{
    use TemplateTrait;
    use RedirectRouteTrait;

    /** @var ?string */
    private $route;

    /**
     * @return string|null
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * @param string|null $route
     */
    public function setRoute(?string $route): void
    {
        $this->route = $route;
    }
}
