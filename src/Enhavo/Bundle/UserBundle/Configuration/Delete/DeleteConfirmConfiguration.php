<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Delete;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\FormTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\MailTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\RedirectRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TemplateTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TranslationDomainTrait;
use Enhavo\Bundle\UserBundle\Configuration\MailConfigurationInterface;

class DeleteConfirmConfiguration implements MailConfigurationInterface
{
    use MailTrait;
    use TranslationDomainTrait;
    use RedirectRouteTrait;
    use FormTrait;
    use TemplateTrait;
}
