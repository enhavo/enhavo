<?php

namespace Enhavo\Bundle\UserBundle\Configuration\ChangeEmail;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\AutoLoginTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\ConfirmationRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\FormTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\MailTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\RedirectRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TemplateTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TranslationDomainTrait;
use Enhavo\Bundle\UserBundle\Configuration\MailConfigurationInterface;

class ChangeEmailConfirmConfiguration implements MailConfigurationInterface
{
    use FormTrait;
    use TemplateTrait;
    use RedirectRouteTrait;
    use MailTrait;
    use AutoLoginTrait;
    use TranslationDomainTrait;
    use ConfirmationRouteTrait;
}
