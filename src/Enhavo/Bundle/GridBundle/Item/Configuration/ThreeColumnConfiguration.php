<?php
/**
 * ThreeColumnConfiguration.php
 *
 * @since 08/08/18
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Model\Column\ThreeColumnItem;
use Enhavo\Bundle\GridBundle\Factory\ThreeColumnItemFactory;
use Enhavo\Bundle\GridBundle\Form\Type\ThreeColumnItemType;
use Enhavo\Bundle\GridBundle\Item\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreeColumnConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => ThreeColumnItem::class,
            'parent' => ThreeColumnItem::class,
            'form' => ThreeColumnItemType::class,
            'factory' => ThreeColumnItemFactory::class,
            'repository' => 'EnhavoGridBundle:ThreeColumn',
            'template' => 'EnhavoGridBundle:Item:three-column.html.twig',
            'form_template' => 'EnhavoGridBundle:Form:item_fields.html.twig',
            'label' => 'three_column.label.three_column',
            'translationDomain' => 'EnhavoGridBundle',
            'groups' => ['default', 'layout']
        ]);
    }

    public function getType()
    {
        return 'three_column';
    }
}