<?php
/**
 * DatabaseVersion.php
 *
 * @since 2016-04-15
 * @author Fabian Liebl <fabian.liebl@xq-web.de>
 */

namespace Enhavo\Bundle\MigrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class DatabaseVersion {
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $version;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}
