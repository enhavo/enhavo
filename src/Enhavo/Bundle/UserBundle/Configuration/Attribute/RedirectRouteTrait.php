<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait RedirectRouteTrait
{
    private ?string $redirectRoute = null;

    public function getRedirectRoute(): ?string
    {
        return $this->redirectRoute;
    }

    public function setRedirectRoute(?string $redirectRoute): void
    {
        $this->redirectRoute = $redirectRoute;
    }
}
