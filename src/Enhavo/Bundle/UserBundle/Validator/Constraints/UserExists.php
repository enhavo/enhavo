<?php

namespace Enhavo\Bundle\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UserExists extends Constraint
{
    public $message = 'enhavo_user.user.not_exists';
}
