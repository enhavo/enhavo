<?php
/**
 * PictureConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Model\Item\PictureItem;
use Enhavo\Bundle\GridBundle\Factory\PictureItemFactory;
use Enhavo\Bundle\GridBundle\Form\Type\PictureItemType;
use Enhavo\Bundle\GridBundle\Item\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PictureConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => PictureItem::class,
            'parent' => PictureItem::class,
            'form' => PictureItemType::class,
            'factory' => PictureItemFactory::class,
            'repository' => 'EnhavoGridBundle:Picture',
            'template' => 'EnhavoGridBundle:Item:picture.html.twig',
            'label' => 'picture.label.picture',
            'translationDomain' => 'EnhavoGridBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'picture';
    }
}