<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CalendarBundle\Validator\Constraints;

/*
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 20.05.16
 * Time: 12:39
 */

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AppointmentDateValidator extends ConstraintValidator
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function validate($appointment, Constraint $constraint)
    {
        $translator = $this->container->get('translator');
        $this->validateDate($appointment->getDateFrom(), $appointment->getDateTo(), $translator, $constraint);
    }

    protected function validateDate($dateFrom, $dateTo, $translator, $constraint)
    {
        if ($dateTo < $dateFrom) {
            $this->context->buildViolation($translator->trans($constraint->datesDoNotMatch, [], 'EnhavoCalendarBundle'))
                ->addViolation();
        }
    }
}
