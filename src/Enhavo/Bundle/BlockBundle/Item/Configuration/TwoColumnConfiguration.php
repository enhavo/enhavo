<?php
/**
 * TwoColumnConfiguration.php
 *
 * @since 08/08/18
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Model\Column\TwoColumnItem;
use Enhavo\Bundle\GridBundle\Factory\TwoColumnItemFactory;
use Enhavo\Bundle\GridBundle\Form\Type\TwoColumnItemType;
use Enhavo\Bundle\GridBundle\Item\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TwoColumnConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => TwoColumnItem::class,
            'parent' => TwoColumnItem::class,
            'form' => TwoColumnItemType::class,
            'factory' => TwoColumnItemFactory::class,
            'repository' => 'EnhavoGridBundle:TwoColumn',
            'template' => 'EnhavoGridBundle:Item:two-column.html.twig',
            'form_template' => 'EnhavoGridBundle:Form:item_fields.html.twig',
            'label' => 'two_column.label.two_column',
            'translationDomain' => 'EnhavoGridBundle',
            'groups' => ['default', 'layout']
        ]);
    }

    public function getType()
    {
        return 'two_column';
    }
}