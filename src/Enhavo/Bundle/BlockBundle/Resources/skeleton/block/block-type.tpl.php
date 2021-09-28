<?= "<?php\n" ?>

namespace <?= $namespace ?>\Block;

use <?= $entity_namespace ?>\<?= $name_camel ?>;
use <?= $factory_namespace ?>\<?= $name_camel ?>Factory;
use <?= $form_namespace ?>\<?= $name_camel ?>Type as <?= $name_camel ?>FormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?= $name_camel ?>Type extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => <?= $name_camel ?>::class,
            'form' => <?= $name_camel ?>FormType::class,
            'factory' => <?= $name_camel ?>Factory::class,
            'template' => 'theme/block/<?= $name_kebab ?>.html.twig',
            'label' => '<?= $name_camel ?>',
            'translationDomain' => <?= $translation_domain ? "'".$translation_domain."'" : 'null' ?>,
            'groups' => ['default', 'content']
        ]);
    }

    public static function getName(): ?string
    {
        return '<?= $name_snake ?>';
    }
}
