<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 04.10.18
 * Time: 23:47
 */

namespace Enhavo\Bundle\MediaBundle\Form\Type;

use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LibraryFileType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    /**
     * @var UrlGeneratorInterface $generator
     */
    private $urlGenerator;

    /**
     * LibraryFileType constructor.
     *
     * @param $dataClass
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct($dataClass, UrlGeneratorInterface $urlGenerator)
    {
        $this->dataClass = $dataClass;
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $resolver = $this->urlGenerator;
        $builder->addEventListener(FormEvents::PRE_SET_DATA,  function(FormEvent $event) use ($resolver) {
            $data = $event->getData();
            $form = $event->getForm();
            $form->add('url', TextType::class, [
                'mapped' => false,
                'data' => $this->urlGenerator->generate($data)
            ]);
        });

        $builder->add('filename', TextType::class, [
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_media_library_file';
    }
}
