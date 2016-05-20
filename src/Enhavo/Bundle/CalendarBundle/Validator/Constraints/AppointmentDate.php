<?php
namespace Enhavo\Bundle\CalendarBundle\Validator\Constraints;
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 20.05.16
 * Time: 12:39
 */
use Symfony\Component\Validator\Constraint;

class AppointmentDate extends Constraint{

    public $datesDoNotMatch = 'appointment.validator.error.dates';

    public function validatedBy()
    {
        return 'valid_appointment_date';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}