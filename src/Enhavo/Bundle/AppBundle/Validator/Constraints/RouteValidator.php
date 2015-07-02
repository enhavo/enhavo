<?php
/**
 * RouteValidator.php
 *
 * @since 19/05/15
 * @author gseidel
 */

namespace enhavo\AdminBundle\Validator\Constraints;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class RouteValidator extends ConstraintValidator
{
    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function validate($value, Constraint $constraint)
    {
        if(!preg_match('#^/[/a-z0-9-_%]*#', $value->getStaticPrefix())) {
            $this->context->buildViolation($constraint->urlValidation)
                ->setParameter('%string%', $value->getStaticPrefix())
                ->addViolation();
        }

        $isUnique = true;
        try {
            $route = $this->router->match($value->getStaticPrefix());
            if($value->getName() && $value->getName() != $route['_route']) {
                $isUnique = false;
            }
        } catch(ResourceNotFoundException $e) { }

        if (!$isUnique) {
            $this->context->buildViolation($constraint->uniqueUrlMessage)
                ->setParameter('%string%', $value->getStaticPrefix())
                ->addViolation();
        }
    }
}