<?php
/**
 * @author blutze-media
 * @since 2022-02-28
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileType extends AbstractType
{
    public function __construct(
        private readonly array $formConfiguration,
        private readonly string $dataClass,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('basename', TextType::class, [
                'label' => 'media_library.form.label.filename',
                'translation_domain' => 'EnhavoMediaLibraryBundle',
            ])
        ;

        $parametersType = $this->formConfiguration[$options['config']]['parameters_type'] ?? null;
        if ($parametersType) {
            $builder->add('parameters', $parametersType);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'config' => 'default',
        ]);
    }
}
