<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Registration;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\AutoLoginTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\ConfirmationRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\FormTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\MailTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\RedirectRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TemplateTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TranslationDomainTrait;
use Enhavo\Bundle\UserBundle\Configuration\MailConfigurationInterface;

class RegistrationRegisterConfiguration implements MailConfigurationInterface
{
    use MailTrait;
    use TemplateTrait;
    use FormTrait;
    use ConfirmationRouteTrait;
    use RedirectRouteTrait;
    use AutoLoginTrait;
    use TranslationDomainTrait;

    /** @var ?boolean */
    private $autoEnabled;

    /** @var ?boolean */
    private $autoVerified;

    /**
     * @return bool
     */
    public function isAutoEnabled(): bool
    {
        return (bool)$this->autoEnabled;
    }

    /**
     * @param bool|null $autoEnabled
     */
    public function setAutoEnabled(?bool $autoEnabled): void
    {
        $this->autoEnabled = $autoEnabled;
    }

    /**
     * @return bool|null
     */
    public function isAutoVerified(): bool
    {
        return (bool)$this->autoVerified;
    }

    /**
     * @param bool|null $autoVerified
     */
    public function setAutoVerified(?bool $autoVerified): void
    {
        $this->autoVerified = $autoVerified;
    }
}
