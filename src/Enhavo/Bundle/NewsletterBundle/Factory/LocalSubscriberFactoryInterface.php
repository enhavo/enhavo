<?php
/**
 * LocalSubscriberFactoryInterface.php
 *
 * @since $date
 * @author $username-media
 */

namespace Enhavo\Bundle\NewsletterBundle\Factory;


use Enhavo\Bundle\NewsletterBundle\Model\LocalSubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;

interface LocalSubscriberFactoryInterface extends FactoryInterface
{
    public function createFrom(SubscriberInterface $subscriber): LocalSubscriberInterface;

    public function createWithGroupId($groupId): LocalSubscriberInterface;
}
