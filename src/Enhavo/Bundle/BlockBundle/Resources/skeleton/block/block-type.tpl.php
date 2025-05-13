<?php echo "<?php\n"; ?>

namespace <?php echo $definition->getNamespace(); ?>\Block;

use <?php echo $definition->getEntityNamespace(); ?>\<?php echo $definition->getCamelName(); ?>;
use <?php echo $definition->getFormNamespace(); ?>\<?php echo $definition->getCamelName(); ?>Type as <?php echo $definition->getCamelName(); ?>FormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?php echo $definition->getCamelName(); ?>Type extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'model' => <?php echo $definition->getCamelName(); ?>::class,
            'form' => <?php echo $definition->getCamelName(); ?>FormType::class,
            'template' => '<?php echo $definition->getTemplateFileName(); ?>',
            'label' => '<?php echo $definition->getLabel(); ?>',
            'translationDomain' => <?php echo $definition->getTranslationDomain() ? "'".$definition->getTranslationDomain()."'" : 'null'; ?>,
            'groups' => <?php echo $definition->getGroupsString() ? $definition->getGroupsString() : '[]'; ?>
        ]);
    }

    public static function getName(): ?string
    {
        return '<?php echo $definition->getSnakeName(); ?>';
    }
}
