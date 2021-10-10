<?php

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
}
