<?php
/**
 * SidebarWidgetType.php
 *
 * @since 25/06/18
 * @author gseidel
 */

namespace Enhavo\Bundle\SidebarBundle\Widget;

use Enhavo\Bundle\AppBundle\Widget\AbstractWidgetType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SidebarWidgetType extends AbstractWidgetType
{
    public function getType()
    {
        return 'sidebar';
    }

    public function createViewData(array $options, $resource = null)
    {
        $sidebar = $this->container->get('enhavo_sidebar.repository.sidebar')->findOneBy([
            'code' => $options['sidebar']
        ]);

        return [
            'sidebar' => $sidebar,
        ];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'template' => 'theme/widget/sidebar.html.twig',
        ])->setRequired([
            'sidebar',
        ]);
    }
}
