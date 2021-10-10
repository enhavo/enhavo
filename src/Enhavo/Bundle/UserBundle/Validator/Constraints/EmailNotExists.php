<?php

namespace Enhavo\Bundle\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class EmailNotExists extends Constraint
{
    public $message = 'enhavo_user.email.exists';
}
