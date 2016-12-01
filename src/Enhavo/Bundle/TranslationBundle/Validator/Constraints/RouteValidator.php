<?php
/**
 * RouteValidator.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Validator\Constraints;

use Enhavo\Bundle\AppBundle\Validator\Constraints\RouteValidator as AppRouteValidator;
use Symfony\Component\Validator\Constraint;

class RouteValidator extends AppRouteValidator
{
    public function validate($value, Constraint $constraint)
    {
        if(is_array($value)) {
            foreach($value as $singleValue) {
                parent::validate($singleValue, $constraint);
            }
        }
    }
}