<?php
/**
 * TextConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Configuration;

use Enhavo\Bundle\BlockBundle\Model\Block\TextBlock;
use Enhavo\Bundle\BlockBundle\Factory\TextBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\TextBlockType;
use Enhavo\Bundle\BlockBundle\Block\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => TextBlock::class,
            'parent' => TextBlock::class,
            'form' => TextBlockType::class,
            'factory' => TextBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:Text',
            'template' => 'EnhavoBlockBundle:Block:text.html.twig',
            'label' => 'text.label.text',
            'translationDomain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'text';
    }
}