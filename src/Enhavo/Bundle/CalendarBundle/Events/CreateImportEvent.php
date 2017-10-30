<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 30.10.17
 * Time: 11:38
 */

namespace Enhavo\Bundle\CalendarBundle\Events;


use Enhavo\Bundle\CalendarBundle\Import\ImporterInterface;
use Enhavo\Bundle\CalendarBundle\Model\AppointmentInterface;
use Symfony\Component\EventDispatcher\Event;

class CreateImportEvent extends Event
{
    /**
     * @var AppointmentInterface
     */
    private $appointment;

    /**
     * @var ImporterInterface
     */
    private $importer;

    public function __construct(AppointmentInterface $appointment, ImporterInterface $importer)
    {
        $this->appointment = $appointment;
        $this->importer = $importer;
    }

    /**
     * @return AppointmentInterface
     */
    public function getAppointment()
    {
        return $this->appointment;
    }

    /**
     * @param AppointmentInterface[] $appointments
     */
    public function setAppointment($appointments)
    {
        $this->appointment = $appointments;
    }

    /**
     * @return ImporterInterface
     */
    public function getImporter()
    {
        return $this->importer;
    }

    /**
     * @param ImporterInterface $importer
     */
    public function setImporter($importer)
    {
        $this->importer = $importer;
    }
}