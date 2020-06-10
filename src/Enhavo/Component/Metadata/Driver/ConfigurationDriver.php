<?php
/**
 * MetadataCollector.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Component\Metadata\Driver;

use Enhavo\Component\Metadata\DriverInterface;

class ConfigurationDriver implements DriverInterface
{
    /**
     * @var array
     */
    private $configuration;

    public function __construct($configuration)
    {
        $this->configuration = $configuration;
    }

    public function load()
    {

    }

    public function getNormalizedData()
    {
        return $this->configuration;
    }
}
