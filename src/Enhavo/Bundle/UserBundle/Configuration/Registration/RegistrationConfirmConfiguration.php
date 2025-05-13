<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Configuration\Registration;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\AutoLoginTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\FormTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\MailTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\RedirectRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TemplateTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TranslationDomainTrait;
use Enhavo\Bundle\UserBundle\Configuration\MailConfigurationInterface;

class RegistrationConfirmConfiguration implements MailConfigurationInterface
{
    use MailTrait;
    use TemplateTrait;
    use FormTrait;
    use AutoLoginTrait;
    use RedirectRouteTrait;
    use TranslationDomainTrait;

    /** @var bool */
    private $autoEnabled = true;

    public function isAutoEnabled(): bool
    {
        return (bool) $this->autoEnabled;
    }

    public function setAutoEnabled(bool $autoEnabled): void
    {
        $this->autoEnabled = $autoEnabled;
    }
}
