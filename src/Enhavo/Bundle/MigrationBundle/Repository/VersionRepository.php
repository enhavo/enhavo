<?php
/**
 * VersionRepository.php
 *
 * @since 2016-04-15
 * @author Fabian Liebl <fabian.liebl@xq-web.de>
 */

namespace Enhavo\Bundle\MigrationBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class VersionRepository extends EntityRepository
{
    public function getVersion()
    {
        $version = $this->find(1);
        if($version) {
            return $this->getVersion();
        }
        return null;
    }
}
