<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): void
    {
        $this->route = $route;
    }
}
