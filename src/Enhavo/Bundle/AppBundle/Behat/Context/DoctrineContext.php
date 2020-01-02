<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 06.03.18
 * Time: 16:12
 */

namespace Enhavo\Bundle\AppBundle\Behat\Context;


class DoctrineContext extends KernelContext
{
    /**
     * @Then I flush doctrine
     */
    public function iFlushDoctrine()
    {
        $this->getManager()->flush();
    }
}
