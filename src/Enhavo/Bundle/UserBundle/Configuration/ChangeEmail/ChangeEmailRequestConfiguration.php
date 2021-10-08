<?php

namespace Enhavo\Bundle\UserBundle\Configuration\ChangeEmail;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\ConfirmationRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\FormTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\MailTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\RedirectRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TemplateTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TranslationDomainTrait;
use Enhavo\Bundle\UserBundle\Configuration\MailConfigurationInterface;

class ChangeEmailRequestConfiguration implements MailConfigurationInterface
{
    use FormTrait;
    use RedirectRouteTrait;
    use TemplateTrait;
    use MailTrait;
    use ConfirmationRouteTrait;
    use TranslationDomainTrait;
}
