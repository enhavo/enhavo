<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 15.04.17
 * Time: 17:39
 */

namespace Enhavo\Bundle\CalendarBundle\Import;

use Enhavo\Bundle\CalendarBundle\Entity\Appointment;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
        $this->importerConfig = $importerConfig;
        $this->container = $container;
    }

    public function import($from = null, $to = null, $filter = [])
    {
        $this->createImporterInstances();

        /** @var ImporterInterface $importerInstance */
        foreach ($this->importerInstances as $importerInstance){
            $externalIdsHandled = [];

            $appointmentsToImport = $importerInstance->import($from, $to, $filter);
            /** @var Appointment $appointmentToImport */
            foreach ($appointmentsToImport as $appointmentToImport){
                /** @var Appointment $appointmentFromDb */
                $appointmentFromDb = $this->container->get('doctrine.orm.entity_manager')
                    ->getRepository(Appointment::class)->findOneBy([
                        'externalId' => $appointmentToImport->getExternalId()
                    ]);

                $externalIdsHandled[] = $appointmentToImport->getExternalId();

                if(!$appointmentFromDb){
                    $this->container->get('doctrine.orm.entity_manager')->persist($appointmentToImport);
                } else {
                    $this->updateAppointment($appointmentFromDb, $appointmentToImport);
                }
            }
            $this->deleteObsoleteAppointments($externalIdsHandled, $importerInstance->getName());
        }
        $this->container->get('doctrine.orm.entity_manager')->flush();
    }

    protected function deleteObsoleteAppointments($externalIdsHandled, $importerName)
    {
        $externalIdsToDelete = [];

        $appointments = $this->container->get('doctrine.orm.entity_manager')
            ->getRepository(Appointment::class)->findBy([
                'importerName' => $importerName
            ]);
        /** @var Appointment $appointment */
        foreach ($appointments as $appointment) {
            if (!in_array($appointment->getExternalId(), $externalIdsHandled)) {
                $externalIdsToDelete[] = $appointment->getExternalId();
            }
        }

        foreach ($externalIdsToDelete as $externalIdToDelete){
            $appointmentToDelete = $this->container->get('doctrine.orm.entity_manager')
                ->getRepository(Appointment::class)->findOneBy([
                    'externalId' => $externalIdToDelete
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
    }

    protected function createImporterInstances()
    {
        foreach ($this->importerConfig as $name => $config){
            if(!$this->isInstanceAlreadyCreated($name)){
                /** @var ImporterInterface $newInstance */
                $newInstance = new $config['class']($name, $config);
                $newInstance->setContainer($this->container);
                $this->importerInstances[] = $newInstance;
            }
        }
    }

    protected function isInstanceAlreadyCreated($name)
    {
        /** @var ImporterInterface $instance */
        foreach ($this->importerInstances as $instance){
            if($instance->getName() === $name){
                return true;
            }
        }
        return false;
    }
}