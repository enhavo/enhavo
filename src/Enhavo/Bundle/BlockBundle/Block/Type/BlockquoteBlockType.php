<?php
/**
 * BlockquoteTextConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Model\Block\BlockquoteBlock;
use Enhavo\Bundle\BlockBundle\Factory\BlockquoteBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockquoteBlockType as BlockquoteBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockquoteBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => BlockquoteBlock::class,
            'parent' => BlockquoteBlock::class,
            'form' => BlockquoteBlockFormType::class,
            'factory' =>  BlockquoteBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:BlockquoteBlock',
            'template' => 'EnhavoBlockBundle:Block:blockquote.html.twig',
            'label' =>  'blockquoteText.label.blockquoteText',
            'translationDomain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'blockquote';
    }
}
