<?php

namespace Enhavo\Bundle\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UserEmail extends Constraint
{
    public $message = 'enhavo_user.email.not_same';
}
