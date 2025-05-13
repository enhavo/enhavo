<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CalendarBundle\Import;

use Enhavo\Bundle\CalendarBundle\Entity\Appointment;
use Enhavo\Bundle\CalendarBundle\Events\CreateImportEvent;
use Enhavo\Bundle\CalendarBundle\Events\ImportEvents;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;

class ImportManager
{
    /**
     * @var array
     */
    protected $importerInstances;

    /**
     * @var array
     */
    protected $importerConfig;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct($importerConfig, $container)
    {
        $this->importerInstances = [];
        if ($importerConfig) {
            $this->importerConfig = $importerConfig;
        } else {
            $this->importerConfig = [];
        }
        $this->container = $container;
    }

    public function import($from = null, $to = null, $filter = [])
    {
        $eventDispatcher = $this->container->get('event_dispatcher');
        $eventDispatcher->dispatch(ImportEvents::PRE_IMPORT, new Event());

        $this->createImporterInstances();

        $importersHandled = [];

        /** @var ImporterInterface $importerInstance */
        foreach ($this->importerInstances as $importerInstance) {
            $externalIdsHandled = [];

            $appointmentsToImport = $importerInstance->import($from, $to, $filter);
            $createImports = [];

            /** @var Appointment $appointmentToImport */
            foreach ($appointmentsToImport as $appointmentToImport) {
                /** @var Appointment $appointmentFromDb */
                $appointmentFromDb = $this->container->get('doctrine.orm.entity_manager')
                    ->getRepository(Appointment::class)->findOneBy([
                        'externalId' => $appointmentToImport->getExternalId(),
                    ]);

                $externalIdsHandled[] = $appointmentToImport->getExternalId();

                if (!$appointmentFromDb) {
                    $this->container->get('doctrine.orm.entity_manager')->persist($appointmentToImport);
                    $eventDispatcher = $this->container->get('event_dispatcher');
                    $eventDispatcher->dispatch(ImportEvents::PRE_CREATE, new CreateImportEvent($appointmentToImport, $importerInstance));
                } else {
                    $this->updateAppointment($appointmentFromDb, $appointmentToImport);
                }
            }

            $this->deleteObsoleteAppointments($externalIdsHandled, $importerInstance->getName());
            $importersHandled[] = $importerInstance->getName();
        }
        $this->deleteObsoleteImporterAppointments($importersHandled);
        $this->container->get('doctrine.orm.entity_manager')->flush();
    }

    protected function deleteObsoleteImporterAppointments($validImporters)
    {
        $obsoleteImporters = [];
        $appointments = $this->container->get('doctrine.orm.entity_manager')->getRepository(Appointment::class)->findAll();
        /** @var Appointment $appointment */
        foreach ($appointments as $appointment) {
            if (!in_array($appointment->getImporterName(), $validImporters)) {
                if (!in_array($appointment->getImporterName(), $obsoleteImporters)) {
                    $obsoleteImporters[] = $appointment->getImporterName();
                }
            }
        }

        foreach ($obsoleteImporters as $obsoleteImporter) {
            $appointments = $this->container->get('doctrine.orm.entity_manager')
                ->getRepository(Appointment::class)->findBy(['importerName' => $obsoleteImporter]);
            foreach ($appointments as $appointment) {
                if ($appointment->getNotImporterHandled()) {
                    continue;
                }
                $this->container->get('doctrine.orm.entity_manager')->remove($appointment);
            }
        }
        $this->container->get('doctrine.orm.entity_manager')->flush();
    }

    protected function deleteObsoleteAppointments($externalIdsHandled, $importerName)
    {
        $externalIdsToDelete = [];

        $appointments = $this->container->get('doctrine.orm.entity_manager')
            ->getRepository(Appointment::class)->findBy([
                'importerName' => $importerName,
            ]);
        /** @var Appointment $appointment */
        foreach ($appointments as $appointment) {
            if (!in_array($appointment->getExternalId(), $externalIdsHandled)) {
                $externalIdsToDelete[] = $appointment->getExternalId();
            }
        }

        foreach ($externalIdsToDelete as $externalIdToDelete) {
            $appointmentToDelete = $this->container->get('doctrine.orm.entity_manager')
                ->getRepository(Appointment::class)->findOneBy([
                    'externalId' => $externalIdToDelete,
                ]);
            $this->container->get('doctrine.orm.entity_manager')->remove($appointmentToDelete);
        }
    }

    protected function updateAppointment(Appointment &$appointmentFromDb, Appointment $appointmentToImport)
    {
        $appointmentFromDb->setDateFrom($appointmentToImport->getDateFrom());
        $appointmentFromDb->setDateTo($appointmentToImport->getDateTo());
        $appointmentFromDb->setTitle($appointmentToImport->getTitle());
        $appointmentFromDb->setTeaser($appointmentToImport->getTeaser());
        $appointmentFromDb->setLocationName($appointmentToImport->getLocationName());
        $appointmentFromDb->setLocationLongitude($appointmentToImport->getLocationLongitude());
        $appointmentFromDb->setLocationLatitude($appointmentToImport->getLocationLatitude());
        $appointmentFromDb->setLocationCity($appointmentToImport->getLocationCity());
        $appointmentFromDb->setLocationCountry($appointmentToImport->getLocationCountry());
        $appointmentFromDb->setLocationStreet($appointmentToImport->getLocationStreet());
        $appointmentFromDb->setLocationStreet($appointmentToImport->getLocationStreet());
    }

    protected function createImporterInstances()
    {
        foreach ($this->importerConfig as $name => $config) {
            if (!$this->isInstanceAlreadyCreated($name)) {
                /** @var ImporterInterface $newInstance */
                $newInstance = new $config['class']($name, $config);
                if ($newInstance instanceof ContainerAwareInterface) {
                    $newInstance->setContainer($this->container);
                }
                $this->importerInstances[] = $newInstance;
            }
        }
    }

    protected function isInstanceAlreadyCreated($name)
    {
        /** @var ImporterInterface $instance */
        foreach ($this->importerInstances as $instance) {
            if ($instance->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    public function addImporterInstance(ImporterInterface $instance)
    {
        if (!$this->isInstanceAlreadyCreated($instance->getName())) {
            $this->importerInstances[] = $instance;
        }
    }
}
