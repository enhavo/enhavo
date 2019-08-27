<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 19.08.19
 * Time: 15:21
 */

namespace Enhavo\Bundle\NewsletterBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'add_box',
            'label' => 'group.label.group',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'route' => 'enhavo_newsletter_group_index',
            'role' => 'ROLE_ENHAVO_NEWSLETTER_GROUP_INDEX',
        ]);
    }

    public function getType()
    {
        return 'newsletter_group';
    }
}
