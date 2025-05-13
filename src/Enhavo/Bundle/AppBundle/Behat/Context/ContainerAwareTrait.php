<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Behat\Context;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * DefaultContext.php
 *
 * @since 27/01/16
 *
 * @author gseidel
 */
trait ContainerAwareTrait
{
    use KernelAwareTrait;

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->kernel->getContainer()->get('test.service_container');
    }

    /**
     * @return object
     */
    public function get($id)
    {
        return $this->getContainer()->get($id);
    }
}
