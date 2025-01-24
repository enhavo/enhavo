<?php

namespace Enhavo\Bundle\UserBundle\Form\Extension;

use Enhavo\Bundle\ResourceBundle\Security\CsrfChecker;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CsrfDisableFormTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private readonly CsrfChecker $csrfChecker,
    )
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => $this->csrfChecker->isEnabled()
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
