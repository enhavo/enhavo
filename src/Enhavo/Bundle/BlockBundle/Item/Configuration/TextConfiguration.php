<?php
/**
 * TextConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Model\Item\TextItem;
use Enhavo\Bundle\GridBundle\Factory\TextItemFactory;
use Enhavo\Bundle\GridBundle\Form\Type\TextItemType;
use Enhavo\Bundle\GridBundle\Item\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => TextItem::class,
            'parent' => TextItem::class,
            'form' => TextItemType::class,
            'factory' => TextItemFactory::class,
            'repository' => 'EnhavoGridBundle:Text',
            'template' => 'EnhavoGridBundle:Item:text.html.twig',
            'label' => 'text.label.text',
            'translationDomain' => 'EnhavoGridBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'text';
    }
}