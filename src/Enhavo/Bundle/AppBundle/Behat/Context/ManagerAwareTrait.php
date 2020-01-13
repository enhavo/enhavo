<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-05
 * Time: 11:27
 */

namespace Enhavo\Bundle\AppBundle\Behat\Context;

use Doctrine\ORM\EntityManagerInterface;

trait ManagerAwareTrait
{
    use ContainerAwareTrait;

    public function getManager(): EntityManagerInterface
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
