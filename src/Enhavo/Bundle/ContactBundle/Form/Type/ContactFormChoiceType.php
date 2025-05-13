<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $resolver->setDefaults([
            'choices' => $this->getChoices(),
            'required' => true,
        ]);
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
