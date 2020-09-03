<?php
/**
 * StorageInterface.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Component\Type\TypeInterface;

interface StorageTypeInterface extends TypeInterface
{
    /**
     * @param SubscriberInterface $subscriber
     */
    public function saveSubscriber(SubscriberInterface $subscriber);

    /**
     * @param SubscriberInterface $subscriber
     * @return boolean
     */
    public function exists(SubscriberInterface $subscriber): bool;
}
