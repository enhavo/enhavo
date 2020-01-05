<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-05
 * Time: 11:27
 */

namespace Enhavo\Bundle\AppBundle\Behat\Context;


trait ManagerAwareTrait
{
    use ContainerAwareTrait;

    public function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
