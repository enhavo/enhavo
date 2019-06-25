<?php
/**
 * CiteTextConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Model\Block\CiteBlock;
use Enhavo\Bundle\BlockBundle\Factory\CiteBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\CiteBlockType as CiteBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CiteBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => CiteBlock::class,
            'parent' => CiteBlock::class,
            'form' => CiteBlockFormType::class,
            'factory' =>  CiteBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:CiteText',
            'template' => 'EnhavoBlockBundle:Block:cite_text.html.twig',
            'label' =>  'citeText.label.citeText',
            'translationDomain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'cite';
    }
}
