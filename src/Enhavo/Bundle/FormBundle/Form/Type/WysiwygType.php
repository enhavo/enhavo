<?php
/**
 * WysiwygType.php
 *
 * @since 31/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class WysiwygType extends AbstractType
{
    public function __construct(
        private $entrypoint,
        private $entrypointBuild
    )
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['config'] = $options['config'];

        $editorEntrypoint = $options['editor_entrypoint'] === null ? $this->entrypoint : $options['editor_entrypoint'];
        $editorEntrypointBuild = $options['editor_entrypoint_build'] === null ? $this->entrypointBuild : $options['editor_entrypoint_build'];
        $view->vars['editor_css'] = null;
        if ($editorEntrypoint) {
            //$view->vars['editor_css'] = $this->entrypointManager->getCssFiles($editorEntrypoint, $editorEntrypointBuild);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'config' => 'default',
            'editor_entrypoint' => null,
            'editor_entrypoint_build' => null
        ));
    }

    public function getParent()
    {
        return TextareaType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_wysiwyg';
    }
}
