<?php
/**
 * CiteTextConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Model\Item\CiteItem;
use Enhavo\Bundle\GridBundle\Factory\CiteItemFactory;
use Enhavo\Bundle\GridBundle\Form\Type\CiteItemType;
use Enhavo\Bundle\GridBundle\Item\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CiteConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => CiteItem::class,
            'parent' => CiteItem::class,
            'form' => CiteItemType::class,
            'factory' =>  CiteItemFactory::class,
            'repository' => 'EnhavoGridBundle:CiteText',
            'template' => 'EnhavoGridBundle:Item:cite_text.html.twig',
            'label' =>  'citeText.label.citeText',
            'translationDomain' => 'EnhavoGridBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'cite';
    }
}