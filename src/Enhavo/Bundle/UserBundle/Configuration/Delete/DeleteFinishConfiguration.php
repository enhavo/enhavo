<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Delete;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\MailTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\RedirectRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TemplateTrait;

class DeleteFinishConfiguration
{
    use MailTrait;
    use RedirectRouteTrait;
    use TemplateTrait;
}
