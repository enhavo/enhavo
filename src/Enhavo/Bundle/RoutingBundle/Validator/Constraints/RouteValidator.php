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

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

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
        if (!preg_match('#^/[/a-z0-9-_%]*#', $value->getStaticPrefix())) {
            $this->context->buildViolation($constraint->urlValidation)
                ->setParameter('%string%', $value->getStaticPrefix())
                ->addViolation();
        }

        $isUnique = true;
        try {
            $route = $this->router->match($value->getStaticPrefix());
            if ($value->getName() && $value->getName() != $route['_route']) {
                $isUnique = false;
            }
        } catch (ResourceNotFoundException $e) {
        }

        if (!$isUnique) {
            $this->context->buildViolation($constraint->uniqueUrlMessage)
                ->setParameter('%string%', $value->getStaticPrefix())
                ->addViolation();
        }
    }
}
