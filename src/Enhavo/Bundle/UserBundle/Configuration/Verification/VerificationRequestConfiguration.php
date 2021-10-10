<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Verification;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\ConfirmationRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\MailTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TemplateTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TranslationDomainTrait;
use Enhavo\Bundle\UserBundle\Configuration\MailConfigurationInterface;

class VerificationRequestConfiguration implements MailConfigurationInterface
{
    use MailTrait;
    use TranslationDomainTrait;
    use ConfirmationRouteTrait;
    use TemplateTrait;

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
