<?php

/**
 * PreviewButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class PreviewDropdownActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'label.preview_frame',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'remove_red_eye',
            'items' => [
                'preview' => [
                    'type' => 'preview',
                ],
                'preview_window' => [
                    'type' => 'preview_window',
                ],
            ]
        ]);
    }

    public static function getParentType(): ?string
    {
        return DropdownActionType::class;
    }

    public static function getName(): ?string
    {
        return 'preview_dropdown';
    }
}
