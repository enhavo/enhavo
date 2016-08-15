<?php

/**
 * DoctrineExtendListener.php
 *
 * @since 28/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\EventListener;

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
class DoctrineSyliusMetaListener implements EventSubscriber
{
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

        if(preg_match('/^sylius_/', $metadata->getTableName())) {
            $tableName = preg_replace('/sylius/', 'shop', $metadata->getTableName());
            $metadata->setPrimaryTable([
                'name' => $tableName
            ]);
        }
    }
}