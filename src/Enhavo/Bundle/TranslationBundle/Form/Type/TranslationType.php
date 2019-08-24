<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-23
 * Time: 01:35
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Type;

use Enhavo\Bundle\TranslationBundle\Validator\Constraints\Translation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends AbstractType
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * TranslationType constructor.
     * @param FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var FormInterface $child */
        $child = $options['child'];

        $builder->addModelTransformer(new CallbackTransformer(
            function ($originalDescription) {
                $data = [
                    'de' => $originalDescription,
                    'en' => 'en title',
                    'fr' => 'fr title',
                ];
                return $data;
            },
            function ($submittedDescription) {
                $data = $submittedDescription;
                return $data['de'];
            }
        ));

        $builder->add('de', TextType::class, $child->getConfig()->getOptions());
        $builder->add('fr', TextType::class, $child->getConfig()->getOptions());
        $builder->add('en', TextType::class, $child->getConfig()->getOptions());
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /** @var FormErrorIterator $errors */
        $errors = $view->vars['errors'];

        $newErrors= [];
        /** @var FormError $error */
        foreach($errors as $error) {
            if(!preg_match('/^\(.+\) /', $error->getMessage())) {
                $newErrors[] = new FormError(
                    sprintf('(de) %s', $error->getMessage()),
                    $error->getMessageTemplate(),
                    $error->getMessageParameters(),
                    $error->getMessagePluralization(),
                    $error->getCause()
                );
            } else {
                $newErrors[] = $error;
            }
        }

        $view->vars['errors'] = new FormErrorIterator($errors->getForm(), $newErrors);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'error_bubbling' => false,
            'constraints' => [
                new Translation([
                    'groups' => ['default']
                ])
            ]
        ]);

        $resolver->setRequired('child');
    }

    public function getBlockPrefix()
    {
        return 'enhavo_translation_tranlsation';
    }
}
