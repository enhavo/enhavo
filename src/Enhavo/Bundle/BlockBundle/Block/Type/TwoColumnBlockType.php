<?php
/**
 * TwoColumnConfiguration.php
 *
 * @since 08/08/18
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Model\Column\TwoColumnBlock;
use Enhavo\Bundle\BlockBundle\Factory\TwoColumnBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\TwoColumnBlockType as TwoColumnBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TwoColumnBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => TwoColumnBlock::class,
            'parent' => TwoColumnBlock::class,
            'form' => TwoColumnBlockFormType::class,
            'factory' => TwoColumnBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:TwoColumn',
            'template' => 'EnhavoBlockBundle:Block:two-column.html.twig',
            'form_template' => 'EnhavoBlockBundle:Form:block_fields.html.twig',
            'label' => 'two_column.label.two_column',
            'translationDomain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'layout']
        ]);
    }

    public function getType()
    {
        return 'two_column';
    }
}
