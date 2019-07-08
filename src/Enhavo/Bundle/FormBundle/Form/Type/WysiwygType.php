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
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupCollectionInterface;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupInterface;

class WysiwygType extends AbstractType
{
    /**
     * @var string
     */
    private $editorEntrypoint;

    /**
     * @var string
     */
    private $editorEntrypointBuild;

    /**
     * @var EntrypointLookupCollectionInterface
     */
    private $entrypointLookup;
    
    /**
     * WysiwygType constructor.
     * @param $editorEntrypoint
     * @param $editorEntrypointBuild
     * @param $entrypointLookup
     */
    public function __construct($editorEntrypoint, $editorEntrypointBuild, EntrypointLookupCollectionInterface $entrypointLookup)
    {
        $this->editorEntrypoint = $editorEntrypoint;
        $this->editorEntrypointBuild = $editorEntrypointBuild;
        $this->entrypointLookup = $entrypointLookup;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['config'] = $options['config'];

        $editorEntrypoint = $options['editor_entrypoint'] === null ? $this->editorEntrypoint : $options['editor_entrypoint'];
        $editorEntrypointBuild = $options['editor_entrypoint_build'] === null ? $this->editorEntrypointBuild : $options['editor_entrypoint_build'];
        $view->vars['editor_css'] = null;
        if($editorEntrypoint) {
            $view->vars['editor_css'] = $this->getEditorEntrypoint($editorEntrypoint, $editorEntrypointBuild);
        }
    }

    private function getEditorEntrypoint($editorEntrypoint, $editorEntrypointBuild)
    {
        return $this->entrypointLookup->getEntrypointLookup($editorEntrypointBuild)->getCssFiles($editorEntrypoint);
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
