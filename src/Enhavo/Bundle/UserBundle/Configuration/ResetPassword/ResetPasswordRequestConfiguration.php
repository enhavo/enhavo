<?php

namespace Enhavo\Bundle\UserBundle\Configuration\ResetPassword;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\ConfirmationRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\FormTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\MailTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\RedirectRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TemplateTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TranslationDomainTrait;
use Enhavo\Bundle\UserBundle\Configuration\MailConfigurationInterface;

class ResetPasswordRequestConfiguration implements MailConfigurationInterface
{
    use RedirectRouteTrait;
    use MailTrait;
    use TemplateTrait;
    use FormTrait;
    use ConfirmationRouteTrait;
    use TranslationDomainTrait;
}
