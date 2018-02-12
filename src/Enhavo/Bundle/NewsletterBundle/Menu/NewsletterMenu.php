<?php
/**
 * NewsletterMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class NewsletterMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setOption('icon', $options, 'newsletter');
        $this->setOption('label', $options, 'newsletter.label.newsletter');
        $this->setOption('translationDomain', $options, 'EnhavoNewsletterBundle');
        $this->setOption('route', $options, 'enhavo_newsletter_newsletter_index');
        $this->setOption('role', $options, 'ROLE_ENHAVO_NEWSLETTER_NEWSLETTER_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'newsletter_newsletter';
    }
}