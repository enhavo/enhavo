<?php
/**
 * TextConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Model\Block\TextBlock;
use Enhavo\Bundle\BlockBundle\Factory\TextBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\TextBlockType as TextBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => TextBlock::class,
            'parent' => TextBlock::class,
            'form' => TextBlockFormType::class,
            'factory' => TextBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:TextBlock',
            'template' => 'theme/block/text.html.twig',
            'label' => 'text.label.text',
            'translationDomain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content', 'simple']
        ]);
    }

    public function getType()
    {
        return 'text';
    }
}
