<?= "<?php\n" ?>

namespace <?= $namespace ?>\Block;

use <?= $namespace ?>\Entity\<?= $block_name ?>;
use <?= $namespace ?>\Factory\<?= $block_name ?>Factory;
use <?= $namespace ?>\Form\Type\<?= $block_name ?>Type as <?= $block_name ?>FormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?= $block_name ?>Type extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => <?= $block_name ?>::class,
            'parent' => <?= $block_name ?>::class,
            'form' => <?= $block_name ?>FormType::class,
            'factory' => <?= $block_name ?>Factory::class,
            'repository' => '<?= $bundle_name ?>:<?= $block_name ?>',
            'template' => '<?= $bundle_name ?>:Theme/Block:<?= $name_kebab ?>.html.twig',
            'label' => '<?= $name_camel ?>',
            'translationDomain' => '<?= $bundle_name ?>',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return '<?= $name_snake ?>';
    }
}
