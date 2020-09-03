<?php
/**
 * User: blutze
 * Date: 2020-08-03
 * Time: 02:14
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Type\StorageType;
use Enhavo\Component\Type\AbstractType;

abstract class AbstractStorageType extends AbstractType implements StorageTypeInterface
{
    /** @var StorageTypeInterface */
    protected $parent;

    public function saveSubscriber(SubscriberInterface $subscriber)
    {
        $this->parent->saveSubscriber($subscriber);
    }

    public function exists(SubscriberInterface $subscriber): bool
    {
        return $this->parent->exists($subscriber);
    }


    public static function getParentType(): ?string
    {
        return StorageType::class;
    }
}
