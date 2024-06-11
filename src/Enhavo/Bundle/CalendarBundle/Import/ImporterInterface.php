<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 15.04.17
 * Time: 16:33
 */

namespace Enhavo\Bundle\CalendarBundle\Import;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface ImporterInterface
{
    /**
     * ImporterInterface constructor.
     *
     * @param string $importerName
     * The importerName is retrieved from the configuration file
     *
     * @param array $config
     */
    public function __construct($importerName, $config, ?HttpClientInterface $client = null);

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @param $filter
     * @return mixed
     * returns Appointment Array
     */
    public function import($from, $to, $filter);

    /**
     * @return mixed
     * returns importerName
     */
    public function getName();

    /**
     * @return mixed
     * returns type prefix, eg. 'facebook_'
     */
    public function getPrefix();

    /**
     * @param ContainerInterface $container
     * Is set by the ImportManager.
     *
     * @return mixed
     */
    public function setContainer(ContainerInterface $container);
}
