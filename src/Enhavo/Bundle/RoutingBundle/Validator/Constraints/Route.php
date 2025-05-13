<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Route extends Constraint
{
    public $uniqueUrlMessage = 'The url "%string%" is already in use.';

    public $urlValidation = 'The url "%string%" is not a valid url.';

    public function validatedBy()
    {
        return 'route';
    }
}
