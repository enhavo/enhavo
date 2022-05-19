<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-13
 * Time: 19:44
 */

namespace Enhavo\Bundle\ThemeBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\ThemeBundle\Model\Entity\Theme;

class ThemeSaveListener
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var boolean
     */
    private $enable;

    /**
     * ThemeListener constructor.
     * @param EntityRepository $repository
     * @param bool $enable
     */
    public function __construct(EntityRepository $repository, bool $enable)
    {
        $this->repository = $repository;
        $this->enable = $enable;
    }

    public function onSave(ResourceEvent $event)
    {
        if(!$this->enable) {
            return;
        }

        $subject = $event->getSubject();
        if(!$subject instanceof Theme) {
            return;
        }

        /** @var Theme[] $themes */
        $themes = $this->repository->findAll();
        foreach($themes as $theme) {
            if($subject->isActive() && $subject->getId() != $theme->getId()) {
                $theme->setActive(false);
            }
        }
    }
}
