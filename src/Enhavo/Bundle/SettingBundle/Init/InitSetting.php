<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SettingBundle\Init;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Init\InitInterface;
use Enhavo\Bundle\AppBundle\Init\Output;
use Enhavo\Bundle\SettingBundle\Entity\Setting;
use Enhavo\Bundle\SettingBundle\Setting\SettingManager;

class InitSetting implements InitInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var SettingManager */
    private $manager;

    /**
     * InitSetting constructor.
     */
    public function __construct(EntityManagerInterface $em, SettingManager $manager)
    {
        $this->em = $em;
        $this->manager = $manager;
    }

    public function init(Output $io)
    {
        foreach ($this->manager->getKeys() as $key) {
            /** @var Setting $setting */
            $change = $this->manager->getSetting($key)->init();
            if ($change) {
                $io->writeln(sprintf('Update setting "%s"', $key));
            }
        }
        $this->em->flush();
    }

    public function getType()
    {
        return 'setting';
    }
}
