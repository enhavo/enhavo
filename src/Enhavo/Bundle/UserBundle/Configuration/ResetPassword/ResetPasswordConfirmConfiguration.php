<?php

namespace Enhavo\Bundle\UserBundle\Configuration\ResetPassword;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\AutoLoginTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\FormTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\RedirectRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TemplateTrait;

class ResetPasswordConfirmConfiguration
{
    use TemplateTrait;
    use FormTrait;
    use AutoLoginTrait;
    use RedirectRouteTrait;
}
