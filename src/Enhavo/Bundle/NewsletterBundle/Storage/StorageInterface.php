<?php
/**
 * StorageInterface.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage;


use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;

interface StorageInterface extends TypeInterface
{
    /**
     * @param SubscriberInterface $subscriber
     */
    public function saveSubscriber(SubscriberInterface $subscriber);

    /**
     * @return boolean
     */
    public function exists(SubscriberInterface $subscriber);
}