<?php
/**
 * User: blutze
 * Date: 2020-08-03
 * Time: 02:14
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage\Type;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\StorageTypeInterface;
use Enhavo\Component\Type\AbstractType;

class StorageType extends AbstractType implements StorageTypeInterface
{
    public function saveSubscriber(SubscriberInterface $subscriber)
    {

    }

    public function exists(SubscriberInterface $subscriber): bool
    {
        return false;
    }

}
