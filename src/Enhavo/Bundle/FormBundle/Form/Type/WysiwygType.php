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
    private $entrypointLookup;
    
    /**
     * WysiwygType constructor.
     * @param $editorEntrypoint
     */
    public function __construct($editorEntrypoint, EntrypointLookupInterface $entrypointLookup)
    {
        $this->editorEntrypoint = $editorEntrypoint;
        $this->entrypointLookup = $entrypointLookup;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['config'] = $options['config'];

        $editorEntrypoint = $options['editor_entrypoint'] === null ? $this->editorEntrypoint : $options['editor_entrypoint'];
        $view->vars['editor_css'] = null;
        if($editorEntrypoint) {
            $view->vars['editor_css'] = $this->getEditorEntrypoint($editorEntrypoint);
        }
    }

    private function getEditorEntrypoint($editorEntrypoint)
    {
        return $this->entrypointLookup->getCssFiles($editorEntrypoint);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'config' => 'default',
            'editor_entrypoint' => null
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
