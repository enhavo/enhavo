<?php
/**
 * MetadataCollector.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Metadata;

class MetadataConfiguration implements MetadataConfigurationInterface
{
    /**
     * @var array
     */
    private $configuration;

    public function __construct($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}