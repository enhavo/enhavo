<?php
/**
 * SubscriberMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class SubscriberMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setDefaultOption('icon', $options, 'user-plus');
        $this->setDefaultOption('label', $options, 'subscriber.label.subscriber');
        $this->setDefaultOption('translationDomain', $options, 'EnhavoNewsletterBundle');
        $this->setDefaultOption('route', $options, 'enhavo_newsletter_subscriber_index');
        $this->setDefaultOption('role', $options, 'ROLE_ENHAVO_NEWSLETTER_SUBSCRIBER_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'newsletter_subscriber';
    }
}