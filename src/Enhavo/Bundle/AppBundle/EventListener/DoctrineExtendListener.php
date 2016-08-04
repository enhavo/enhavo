<?php

/**
 * DoctrineExtendListener.php
 *
 * @since 28/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class DoctrineExtendListener
 *
 * Check if a target class was extended and then add a single table inheritance
 * to that class and its metadata.
 *
 * @package Enhavo\Bundle\ArticleBundle\EventListener
 */
class DoctrineExtendListener implements EventSubscriber
{
    /**
     * @var string
     */
    protected $targetClass;

    /**
     * @var string
     */
    protected $extendedClass;

    /**
     * @var boolean
     */
    protected $extend;

    public function __construct($targetClass, $extendedClass, $extend)
    {
        $this->targetClass = $targetClass;
        $this->extendedClass = $extendedClass;
        $this->extend = $extend;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        if ($metadata->getName() != $this->targetClass) {
            return;
        }

        if($this->targetClass == $this->extendedClass || !$this->extend) {
            return;
        }

        $metadata->setInheritanceType(ClassMetadata::INHERITANCE_TYPE_SINGLE_TABLE);
        $metadata->setDiscriminatorColumn([
            'name' => 'discr',
            'type' => 'string',
            'length' => 6
        ]);

        $metadata->addDiscriminatorMapClass('target', $this->targetClass);
        $metadata->addDiscriminatorMapClass('extend', $this->extendedClass);
    }
}