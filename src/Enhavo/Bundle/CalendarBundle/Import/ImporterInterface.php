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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface ImporterInterface
{
    /**
     * ImporterInterface constructor.
     *
     * @param string $importerName
     *                             The importerName is retrieved from the configuration file
     * @param array  $config
     */
    public function __construct($importerName, $config, ?HttpClientInterface $client = null);

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return mixed
     *               returns Appointment Array
     */
    public function import($from, $to, $filter);

    /**
     * @return mixed
     *               returns importerName
     */
    public function getName();

    /**
     * @return mixed
     *               returns type prefix, eg. 'facebook_'
     */
    public function getPrefix();

    /**
     * @param ContainerInterface $container
     *                                      Is set by the ImportManager
     */
    public function setContainer(ContainerInterface $container);
}
