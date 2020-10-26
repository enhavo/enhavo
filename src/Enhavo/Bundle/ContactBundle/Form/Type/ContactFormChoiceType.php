<?php


namespace Enhavo\Bundle\ContactBundle\Form\Type;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactFormChoiceType extends ChoiceType
{
    /**
     * @var array
     */
    private $forms;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(array $forms, TranslatorInterface $translator)
    {
        parent::__construct();
        $this->forms = $forms;
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'choices' => $this->getChoices(),
            'required' => true
        ));
    }

    private function getChoices()
    {
        $choices = [];
        foreach ($this->forms as $key => $form) {
            $label = $this->translator->trans($form['label'], [], $form['translation_domain']);
            $choices[$label] = $key;
        }
        return $choices;
    }
}
