<?php
/**
 * OneColumnConfiguration.php
 *
 * @since 08/08/18
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Entity\OneColumnItem;
use Enhavo\Bundle\GridBundle\Factory\OneColumnItemFactory;
use Enhavo\Bundle\GridBundle\Form\Type\OneColumnItemType;
use Enhavo\Bundle\GridBundle\Item\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OneColumnConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => OneColumnItem::class,
            'parent' => OneColumnItem::class,
            'form' => OneColumnItemType::class,
            'factory' => OneColumnItemFactory::class,
            'repository' => 'EnhavoGridBundle:OneColumn',
            'template' => 'EnhavoGridBundle:Item:one_column.html.twig',
            'form_template' => 'EnhavoGridBundle:Form:item_fields.html.twig',
            'label' => 'one_column.label.one_column',
            'translationDomain' => 'EnhavoGridBundle',
        ]);
    }

    public function getType()
    {
        return 'one_column';
    }
}