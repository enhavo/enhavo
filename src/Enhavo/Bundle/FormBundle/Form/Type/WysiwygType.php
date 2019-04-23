<?php
/**
 * WysiwygType.php
 *
 * @since 31/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Config\WysiwygOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Enhavo\Bundle\FormBundle\Form\Config\WysiwygConfig;

class WysiwygType extends AbstractType
{
    /**
     * @var WysiwygConfig
     */
    private $config;

    public function __construct(WysiwygConfig $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $option = new WysiwygOption();
        $option->setFormats($options['formats']);
        $option->setToolbar1($options['toolbar1']);
        $option->setToolbar2($options['toolbar2']);
        $option->setHeight($options['height']);
        $option->setContentCss($options['content_css']);
        $view->vars['config'] = $this->config->getData($option);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'formats' => array(),
            'toolbar1' => null,
            'toolbar2' => null,
            'height' => null,
            'content_css' => null
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