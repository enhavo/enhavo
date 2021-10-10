<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Delete;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\MailTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\RedirectRouteTrait;

class DeleteFinishConfiguration
{
    use MailTrait;
    use RedirectRouteTrait;
}
