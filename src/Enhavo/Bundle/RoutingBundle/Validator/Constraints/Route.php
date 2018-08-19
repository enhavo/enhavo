<?php
/**
 * Route.php
 *
 * @since 19/05/15
 * @author gseidel
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